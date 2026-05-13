<?php

namespace App\Controllers;

class RhController extends BaseController
{
    private function getTestUser()
    {
        return [
            'id' => 2,
            'prenom' => 'Marie',
            'nom' => 'Martin',
            'email' => 'rh@techmada.mg',
            'departement' => 'RH',
            'date_embauche_fmt' => '10/06/2020',
        ];
    }

    public function dashboard()
    {
        $db = db_connect();

        $anneeRow = $db->table('soldes')
            ->selectMax('annee')
            ->get()
            ->getRowArray();
        $annee = (int) ($anneeRow['annee'] ?? date('Y'));

        $statusAttente = $db->table('status_conges')
            ->select('id')
            ->where('nom', 'En attente')
            ->get()
            ->getRowArray();
        $statusApprouve = $db->table('status_conges')
            ->select('id')
            ->where('nom', 'Approuve')
            ->get()
            ->getRowArray();
        $statusRefuse = $db->table('status_conges')
            ->select('id')
            ->where('nom', 'Refuse')
            ->get()
            ->getRowArray();

        $statusAttenteId = $statusAttente['id'] ?? null;
        $statusApprouveId = $statusApprouve['id'] ?? null;
        $statusRefuseId = $statusRefuse['id'] ?? null;

        $nb_attente = $statusAttenteId
            ? $db->table('conges')->where('status_id', $statusAttenteId)->countAllResults()
            : 0;

        $nb_employes = $db->table('employes')->where('actif', 1)->countAllResults();

        $nb_approuvees = $statusApprouveId
            ? $db->table('conges')->where('status_id', $statusApprouveId)->countAllResults()
            : 0;

        $nb_refusees = $statusRefuseId
            ? $db->table('conges')->where('status_id', $statusRefuseId)->countAllResults()
            : 0;

        $congesPrisRows = [];
        if ($statusApprouveId) {
            $congesPrisRows = $db->table('conges')
                ->select('employe_id, type_conge_id, SUM(nb_jours) as jours_pris')
                ->where('status_id', $statusApprouveId)
                ->groupBy('employe_id, type_conge_id')
                ->get()
                ->getResultArray();
        }

        $congesPris = [];
        foreach ($congesPrisRows as $row) {
            $key = $row['employe_id'] . '-' . $row['type_conge_id'];
            $congesPris[$key] = (int) ($row['jours_pris'] ?? 0);
        }

        $soldesRows = $db->table('soldes')
            ->select('employe_id, type_conge_id, jours_attribues, jours_pris')
            ->where('annee', $annee)
            ->get()
            ->getResultArray();

        $soldes = [];
        foreach ($soldesRows as $row) {
            $key = $row['employe_id'] . '-' . $row['type_conge_id'];
            $joursAttribues = (int) ($row['jours_attribues'] ?? 0);
            $joursUtilises = array_key_exists($key, $congesPris)
                ? $congesPris[$key]
                : (int) ($row['jours_pris'] ?? 0);
            $soldes[$key] = $joursAttribues - $joursUtilises;
        }

        $rows = $db->table('conges c')
            ->select('c.id, c.employe_id, c.type_conge_id, c.date_debut, c.date_fin, c.nb_jours, c.status_id, c.traite_par')
            ->select('e.nom, e.prenom, d.nom as departement, tc.nom as type_conge')
            ->select('s.nom as statut_nom, tp.nom as traite_nom, tp.prenom as traite_prenom')
            ->join('employes e', 'e.id = c.employe_id', 'left')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->join('types_conges tc', 'tc.id = c.type_conge_id', 'left')
            ->join('status_conges s', 's.id = c.status_id', 'left')
            ->join('employes tp', 'tp.id = c.traite_par', 'left')
            ->orderBy('c.date_debut', 'ASC')
            ->get()
            ->getResultArray();

        $formatDate = static function (?string $date): string {
            if (!$date) {
                return '';
            }

            return date('d/m/Y', strtotime($date));
        };

        $normalize = static function (?string $statutNom): string {
            $statutNom = mb_strtolower(trim((string) $statutNom));
            if ($statutNom === '') {
                return 'en_attente';
            }
            if (str_contains($statutNom, 'attente')) {
                return 'en_attente';
            }
            if (str_contains($statutNom, 'approuv')) {
                return 'approuvee';
            }
            if (str_contains($statutNom, 'refus')) {
                return 'refusee';
            }

            return 'en_attente';
        };

        $demandes_attente = [];
        foreach ($rows as $row) {
            $typeLabel = $row['type_conge'] ?? '';
            $badgeClass = 'badge-other';
            if (stripos($typeLabel, 'annuel') !== false) {
                $badgeClass = 'badge-annual';
            } elseif (stripos($typeLabel, 'maladie') !== false) {
                $badgeClass = 'badge-sick';
            }

            $statutKey = $normalize($row['statut_nom'] ?? null);
            $statutLabel = $row['statut_nom'] ?: 'En attente';
            $statutClass = match ($statutKey) {
                'approuvee' => 'approved',
                'refusee' => 'rejected',
                default => 'pending',
            };

            $soldeKey = $row['employe_id'] . '-' . $row['type_conge_id'];
            $soldeDispo = array_key_exists($soldeKey, $soldes) ? $soldes[$soldeKey] : null;
            $nbJours = (int) ($row['nb_jours'] ?? 0);

            $traitePar = trim(($row['traite_prenom'] ?? '') . ' ' . ($row['traite_nom'] ?? ''));

            $demandes_attente[] = [
                'id' => $row['id'],
                'prenom' => $row['prenom'] ?? '',
                'nom' => $row['nom'] ?? '',
                'departement' => $row['departement'] ?? '',
                'type_label' => $typeLabel,
                'badge_class' => $badgeClass,
                'date_debut_fmt' => $formatDate($row['date_debut'] ?? null),
                'date_fin_fmt' => $formatDate($row['date_fin'] ?? null),
                'nb_jours' => $nbJours,
                'solde_dispo' => $soldeDispo,
                'solde_ok' => $soldeDispo !== null ? $soldeDispo >= $nbJours : false,
                'statut' => $statutKey,
                'statut_label' => $statutLabel,
                'statut_class' => $statutClass,
                'traite_par' => $traitePar,
            ];
        }

        return view('rh/dashboard', [
            'user' => $this->getTestUser(),
            'nb_attente' => $nb_attente,
            'nb_approuvees' => $nb_approuvees,
            'nb_refusees' => $nb_refusees,
            'nb_employes' => $nb_employes,
            'demandes_attente' => $demandes_attente,
        ]);
    }

    public function demandes()
    {
        $db = db_connect();

        $anneeRow = $db->table('soldes')
            ->selectMax('annee')
            ->get()
            ->getRowArray();
        $annee = (int) ($anneeRow['annee'] ?? date('Y'));

        $statusApprouve = $db->table('status_conges')
            ->select('id')
            ->where('nom', 'Approuve')
            ->get()
            ->getRowArray();
        $statusApprouveId = $statusApprouve['id'] ?? null;

        $congesPrisRows = [];
        if ($statusApprouveId) {
            $congesPrisRows = $db->table('conges')
                ->select('employe_id, type_conge_id, SUM(nb_jours) as jours_pris')
                ->where('status_id', $statusApprouveId)
                ->groupBy('employe_id, type_conge_id')
                ->get()
                ->getResultArray();
        }

        $congesPris = [];
        foreach ($congesPrisRows as $row) {
            $key = $row['employe_id'] . '-' . $row['type_conge_id'];
            $congesPris[$key] = (int) ($row['jours_pris'] ?? 0);
        }

        $soldesRows = $db->table('soldes')
            ->select('employe_id, type_conge_id, jours_attribues, jours_pris')
            ->where('annee', $annee)
            ->get()
            ->getResultArray();

        $soldes = [];
        foreach ($soldesRows as $row) {
            $key = $row['employe_id'] . '-' . $row['type_conge_id'];
            $joursAttribues = (int) ($row['jours_attribues'] ?? 0);
            $joursUtilises = array_key_exists($key, $congesPris)
                ? $congesPris[$key]
                : (int) ($row['jours_pris'] ?? 0);
            $soldes[$key] = $joursAttribues - $joursUtilises;
        }

        $rows = $db->table('conges c')
            ->select('c.id, c.employe_id, c.type_conge_id, c.date_debut, c.date_fin, c.nb_jours, c.status_id, c.traite_par')
            ->select('e.nom, e.prenom, d.nom as departement, tc.nom as type_conge')
            ->select('s.nom as statut_nom, tp.nom as traite_nom, tp.prenom as traite_prenom')
            ->join('employes e', 'e.id = c.employe_id', 'left')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->join('types_conges tc', 'tc.id = c.type_conge_id', 'left')
            ->join('status_conges s', 's.id = c.status_id', 'left')
            ->join('employes tp', 'tp.id = c.traite_par', 'left')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $formatDate = static function (?string $date): string {
            if (!$date) {
                return '';
            }

            return date('d/m/Y', strtotime($date));
        };

        $normalize = static function (?string $statutNom): string {
            $statutNom = mb_strtolower(trim((string) $statutNom));
            if ($statutNom === '') {
                return 'en_attente';
            }
            if (str_contains($statutNom, 'attente')) {
                return 'en_attente';
            }
            if (str_contains($statutNom, 'approuv')) {
                return 'approuvee';
            }
            if (str_contains($statutNom, 'refus')) {
                return 'refusee';
            }

            return 'en_attente';
        };

        $demandes = [];
        foreach ($rows as $row) {
            $typeLabel = $row['type_conge'] ?? '';
            $badgeClass = 'badge-other';
            if (stripos($typeLabel, 'annuel') !== false) {
                $badgeClass = 'badge-annual';
            } elseif (stripos($typeLabel, 'maladie') !== false) {
                $badgeClass = 'badge-sick';
            }

            $statutKey = $normalize($row['statut_nom'] ?? null);
            $statutLabel = $row['statut_nom'] ?: 'En attente';
            $statutClass = match ($statutKey) {
                'approuvee' => 'approved',
                'refusee' => 'rejected',
                default => 'pending',
            };

            $soldeKey = $row['employe_id'] . '-' . $row['type_conge_id'];
            $soldeDispo = array_key_exists($soldeKey, $soldes) ? $soldes[$soldeKey] : null;
            $nbJours = (int) ($row['nb_jours'] ?? 0);

            $traitePar = trim(($row['traite_prenom'] ?? '') . ' ' . ($row['traite_nom'] ?? ''));

            $demandes[] = [
                'id' => $row['id'],
                'prenom' => $row['prenom'] ?? '',
                'nom' => $row['nom'] ?? '',
                'departement' => $row['departement'] ?? '',
                'type_label' => $typeLabel,
                'badge_class' => $badgeClass,
                'date_debut_fmt' => $formatDate($row['date_debut'] ?? null),
                'date_fin_fmt' => $formatDate($row['date_fin'] ?? null),
                'nb_jours' => $nbJours,
                'solde_dispo' => $soldeDispo,
                'solde_ok' => $soldeDispo !== null ? $soldeDispo >= $nbJours : false,
                'statut' => $statutKey,
                'statut_label' => $statutLabel,
                'statut_class' => $statutClass,
                'traite_par' => $traitePar,
            ];
        }

        $total = count($demandes);
        $nb_attente = count(array_filter($demandes, static fn ($d) => $d['statut'] === 'en_attente'));
        $nb_approuvees = count(array_filter($demandes, static fn ($d) => $d['statut'] === 'approuvee'));
        $nb_refusees = count(array_filter($demandes, static fn ($d) => $d['statut'] === 'refusee'));

        $departementsRows = $db->table('departements')
            ->select('nom')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();

        $departements = array_map(static fn ($row) => $row['nom'], $departementsRows);

        return view('rh/demandes', [
            'user' => $this->getTestUser(),
            'demandes' => $demandes,
            'total' => $total,
            'nb_attente' => $nb_attente,
            'nb_approuvees' => $nb_approuvees,
            'nb_refusees' => $nb_refusees,
            'departements' => $departements,
        ]);
    }
}
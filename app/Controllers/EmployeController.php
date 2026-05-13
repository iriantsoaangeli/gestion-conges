<?php

namespace App\Controllers;

use App\Models\Employe;
use App\Models\Conge;
use App\Models\Solde;
use App\Models\TypeConges;

class EmployeController extends BaseController
{
    protected $employeModel;
    protected $congeModel;
    protected $soldeModel;

    public function __construct()
    {
        $this->employeModel = new Employe();
        $this->congeModel = new Conge();
        $this->soldeModel = new Solde();
    }

    private function getUserData()
    {
        $userId = session()->get('user_id');
        $user = $this->employeModel
            ->select('employes.*, departements.nom as departement_name')
            ->join('departements', 'employes.departement_id = departements.id', 'left')
            ->find($userId);

        if (!$user) {
            return null;
        }

        $user['departement'] = $user['departement_name'] ?? '';
        $user['date_embauche_fmt'] = !empty($user['date_embauche']) ? date('d/m/Y', strtotime($user['date_embauche'])) : '';

        return $user;
    }

    private function getSoldesData(): array
    {
        $userId = session()->get('user_id');
        $annee = date('Y');
        
        $soldes = $this->soldeModel
            ->select('soldes.*, types_conges.nom')
            ->join('types_conges', 'soldes.type_conge_id = types_conges.id')
            ->where('soldes.employe_id', $userId)
            ->where('soldes.annee', $annee)
            ->findAll();

        $result = [];
        foreach ($soldes as $solde) {
            $result[] = [
                'id' => $solde['id'],
                'type' => $solde['nom'],
                'total' => $solde['jours_attribues'],
                'pris' => $solde['jours_pris'],
                'restant' => $solde['jours_attribues'] - $solde['jours_pris'],
            ];
        }
        
        return $result;
    }

    private function getDemandsData(): array
    {
        $userId = session()->get('user_id');
        
        $conges = $this->congeModel
            ->select('conges.*, types_conges.nom as type_nom, status_conges.nom as status_nom')
            ->join('types_conges', 'conges.type_conge_id = types_conges.id')
            ->join('status_conges', 'conges.status_id = status_conges.id')
            ->where('conges.employe_id', $userId)
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();

        $result = [];
        $statusMap = [
            'En attente' => ['label' => 'En attente', 'class' => 'statut-warn', 'badge' => 'badge-amber'],
            'Approuvé' => ['label' => 'Approuvée', 'class' => 'statut-ok', 'badge' => 'badge-green'],
            'Refusé' => ['label' => 'Refusée', 'class' => 'statut-danger', 'badge' => 'badge-red'],
        ];

        foreach ($conges as $conge) {
            $statusKey = $conge['status_nom'];
            $statusInfo = $statusMap[$statusKey] ?? ['label' => $statusKey, 'class' => 'statut-default', 'badge' => 'badge-gray'];
            
            $result[] = [
                'id' => $conge['id'],
                'type_label' => $conge['type_nom'],
                'statut' => strtolower(str_replace(' ', '_', $conge['status_nom'])),
                'statut_label' => $statusInfo['label'],
                'badge_class' => $statusInfo['badge'],
                'statut_class' => $statusInfo['class'],
                'date_debut_fmt' => date('d/m/Y', strtotime($conge['date_debut'])),
                'date_fin_fmt' => date('d/m/Y', strtotime($conge['date_fin'])),
                'nb_jours' => $conge['nb_jours'],
                'commentaire_rh' => $conge['commentaire_rh'] ?? '',
            ];
        }
        
        return $result;
    }

    public function dashboard()
    {
        $user = $this->getUserData();
        $userId = session()->get('user_id');
        $soldes = $this->getSoldesData();
        $demandes = $this->getDemandsData();

        // Calculs statistiques
        $stats = $this->congeModel
            ->select('status_conges.id as status_id, COUNT(*) as count')
            ->join('status_conges', 'conges.status_id = status_conges.id')
            ->where('conges.employe_id', $userId)
            ->groupBy('conges.status_id')
            ->findAll();

        $stats_map = ['attente' => 0, 'approuvees' => 0, 'refusees' => 0];
        foreach ($stats as $stat) {
            if ($stat['status_id'] == 1) $stats_map['attente'] = $stat['count'];
            else if ($stat['status_id'] == 2) $stats_map['approuvees'] = $stat['count'];
            else if ($stat['status_id'] == 3) $stats_map['refusees'] = $stat['count'];
        }

        // Trouver les jours annuels
        $solde_annuel = 0;
        $solde_total = 0;
        foreach ($soldes as $s) {
            if (str_contains(strtolower($s['type']), 'annuel')) {
                $solde_annuel = $s['restant'];
                $solde_total = $s['total'];
                break;
            }
        }
        
        return view('employe/dashboard', [
            'user' => $user,
            'soldes' => $soldes,
            'dernieres_demandes' => array_slice($demandes, 0, 5),
            'nb_attente' => $stats_map['attente'],
            'nb_approuvees' => $stats_map['approuvees'],
            'nb_refusees' => $stats_map['refusees'],
            'solde_annuel' => $solde_annuel,
            'solde_total' => $solde_total,
        ]);
    }

    public function conges()
    {
        $user = $this->getUserData();
        $demandes = $this->getDemandsData();

        $attente = count(array_filter($demandes, fn($d) => str_contains($d['statut'], 'attente')));
        
        return view('employe/conge_index', [
            'user' => $user,
            'badge_attente' => $attente,
            'demandes' => $demandes,
        ]);
    }

    public function congeCreate()
    {
        $user = $this->getUserData();
        $typeCongesModel = new TypeConges();
        $types = $typeCongesModel->findAll();
        
        $userId = session()->get('user_id');
        $annee = date('Y');
        
        $types_conge = [];
        foreach ($types as $type) {
            $solde = $this->soldeModel
                ->where('employe_id', $userId)
                ->where('type_conge_id', $type['id'])
                ->where('annee', $annee)
                ->first();
            
            $restant = 0;
            if ($solde) {
                $restant = $solde['jours_attribues'] - $solde['jours_pris'];
            }
            
            $types_conge[] = [
                'id' => $type['id'],
                'libelle' => $type['nom'],
                'solde_restant' => $restant,
            ];
        }
        
        return view('employe/conge_create', [
            'user' => $user,
            'types_conge' => $types_conge,
        ]);
    }

    public function profil()
    {
        $user = $this->getUserData();
        $soldes = $this->getSoldesData();
        $userId = session()->get('user_id');

        $stats = $this->congeModel
            ->select('status_conges.id as status_id, COUNT(*) as count')
            ->join('status_conges', 'conges.status_id = status_conges.id')
            ->where('conges.employe_id', $userId)
            ->groupBy('conges.status_id')
            ->findAll();

        $stats_map = ['total' => 0, 'approuvees' => 0, 'refusees' => 0, 'attente' => 0];
        foreach ($stats as $stat) {
            $stats_map['total'] += $stat['count'];
            if ($stat['status_id'] == 1) $stats_map['attente'] = $stat['count'];
            else if ($stat['status_id'] == 2) $stats_map['approuvees'] = $stat['count'];
            else if ($stat['status_id'] == 3) $stats_map['refusees'] = $stat['count'];
        }
        
        return view('employe/profil', [
            'user' => $user,
            'soldes' => $soldes,
            'stats' => $stats_map,
        ]);
    }

    public function congeStore()
    {
        $userId = session()->get('user_id');
        
        $type_conge_id = $this->request->getPost('type_conge');
        $date_debut = $this->request->getPost('date_debut');
        $date_fin = $this->request->getPost('date_fin');
        $motif = $this->request->getPost('motif');

        if (!$type_conge_id || !$date_debut || !$date_fin) {
            return redirect()->to('/employe/conges')->with('error', 'Tous les champs sont requis.');
        }

        $debut = strtotime($date_debut);
        $fin = strtotime($date_fin);
        $nb_jours = floor(($fin - $debut) / (60 * 60 * 24)) + 1;

        $data = [
            'employe_id' => $userId,
            'type_conge_id' => $type_conge_id,
            'date_debut' => date('Y-m-d', $debut),
            'date_fin' => date('Y-m-d', $fin),
            'nb_jours' => $nb_jours,
            'motif' => $motif,
            'status_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->congeModel->insert($data)) {
            return redirect()->to('/employe/conges')->with('success', 'Demande de congé enregistrée.');
        } else {
            return redirect()->to('/employe/conges')->with('error', 'Erreur lors de l\'enregistrement.');
        }
    }

    public function congeAnnuler(int $id)
    {
        $userId = session()->get('user_id');
        
        $conge = $this->congeModel->find($id);
        
        if (!$conge || $conge['employe_id'] != $userId) {
            return redirect()->to('/employe/conges')->with('error', 'Demande non trouvée.');
        }

        if ($conge['status_id'] != 1) {
            return redirect()->to('/employe/conges')->with('error', 'Seules les demandes en attente peuvent être annulées.');
        }

        if ($this->congeModel->update($id, ['status_id' => 3])) {
            return redirect()->to('/employe/conges')->with('success', 'Demande annulée.');
        } else {
            return redirect()->to('/employe/conges')->with('error', 'Erreur lors de l\'annulation.');
        }
    }

    public function profilUpdate()
    {
        $userId = session()->get('user_id');
        
        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');

        if (!$nom || !$prenom) {
            return redirect()->to('/employe/profil')->with('error', 'Tous les champs sont requis.');
        }

        if ($this->employeModel->update($userId, ['nom' => $nom, 'prenom' => $prenom])) {
            return redirect()->to('/employe/profil')->with('success', 'Profil mis à jour.');
        } else {
            return redirect()->to('/employe/profil')->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    public function profilPassword()
    {
        $userId = session()->get('user_id');
        
        $old_password = $this->request->getPost('old_password');
        $new_password = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');

        if (!$old_password || !$new_password || !$confirm_password) {
            return redirect()->to('/employe/profil')->with('error', 'Tous les champs sont requis.');
        }

        if ($new_password !== $confirm_password) {
            return redirect()->to('/employe/profil')->with('error', 'Les mots de passe ne correspondent pas.');
        }

        $user = $this->employeModel->find($userId);
        
        if (!password_verify($old_password, $user['password'])) {
            return redirect()->to('/employe/profil')->with('error', 'Ancien mot de passe incorrect.');
        }

        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        
        if ($this->employeModel->update($userId, ['password' => $hashed])) {
            return redirect()->to('/employe/profil')->with('success', 'Mot de passe mis à jour.');
        } else {
            return redirect()->to('/employe/profil')->with('error', 'Erreur lors de la mise à jour.');
        }
    }
}
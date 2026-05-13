<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        return view('admin/dashboard', [
            'nb_employes' => 45,
            'nb_attente' => 5,
            'nb_approuvees_mois' => 12,
            'nb_absents' => 3,
            'diff_approuvees' => 2,
            'nb_departements' => 4,
            'employes_new' => 2,
            'nb_soldes_critiques' => 2,
            'demandes_recentes' => [
                [
                    'id' => 1,
                    'prenom' => 'Jean',
                    'nom' => 'Dupont',
                    'type_label' => 'Congé annuel',
                    'badge_class' => 'badge-annual',
                    'nb_jours' => 5,
                    'statut' => 'en_attente',
                    'statut_label' => 'En attente',
                    'statut_class' => 'pending',
                ],
            ],
            'absents' => [
                ['id' => 1, 'prenom' => 'Paul', 'nom' => 'Durand', 'type_label' => 'Congé annuel', 'retour_fmt' => '15/05/2026'],
            ],
        ]);
    }

    public function demandes()
    {
        $demandes = [
            [
                'id' => 1,
                'prenom' => 'Jean',
                'nom' => 'Dupont',
                'departement' => 'Informatique',
                'type_label' => 'Congé annuel',
                'badge_class' => 'badge-annual',
                'date_debut_fmt' => '15/05/2026',
                'date_fin_fmt' => '20/05/2026',
                'nb_jours' => 5,
                'solde_dispo' => 15,
                'solde_ok' => true,
                'statut' => 'en_attente',
                'statut_label' => 'En attente',
                'statut_class' => 'pending',
            ],
            [
                'id' => 2,
                'prenom' => 'Marie',
                'nom' => 'Martin',
                'departement' => 'RH',
                'type_label' => 'Congé maladie',
                'badge_class' => 'badge-sick',
                'date_debut_fmt' => '10/05/2026',
                'date_fin_fmt' => '12/05/2026',
                'nb_jours' => 2,
                'solde_dispo' => 5,
                'solde_ok' => true,
                'statut' => 'approuvee',
                'statut_label' => 'Approuvée',
                'statut_class' => 'approved',
            ],
        ];

        return view('admin/demandes', [
            'demandes' => $demandes,
            'total' => 12,
            'nb_attente' => 5,
            'nb_approuvees' => 4,
            'nb_refusees' => 3,
            'departements' => [
                ['id' => 1, 'nom' => 'Informatique'],
                ['id' => 2, 'nom' => 'RH'],
                ['id' => 3, 'nom' => 'Finance'],
                ['id' => 4, 'nom' => 'Marketing'],
            ],
        ]);
    }

    public function employes()
    {
        return view('admin/employes', [
            'employes' => [
                ['id' => 1, 'prenom' => 'Jean', 'nom' => 'Dupont', 'email' => 'jean@techmada.mg', 'departement' => 'Informatique', 'poste' => 'Développeur'],
                ['id' => 2, 'prenom' => 'Marie', 'nom' => 'Martin', 'email' => 'marie@techmada.mg', 'departement' => 'RH', 'poste' => 'RH Manager'],
                ['id' => 3, 'prenom' => 'Paul', 'nom' => 'Durand', 'email' => 'paul@techmada.mg', 'departement' => 'Finance', 'poste' => 'Comptable'],
            ],
            'departements' => ['Informatique', 'RH', 'Finance', 'Marketing'],
        ]);
    }
}
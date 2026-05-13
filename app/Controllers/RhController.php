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
        return view('rh/dashboard', [
            'user' => $this->getTestUser(),
            'nb_attente' => 3,
            'nb_approuvees' => 8,
            'nb_absents' => 2,
            'nb_employes' => 45,
            'demandes_attente' => [
                ['id' => 1, 'prenom' => 'Jean', 'nom' => 'Dupont', 'type' => 'Congé annuel', 'date_debut_fmt' => '15/05/2026', 'date_fin_fmt' => '20/05/2026'],
                ['id' => 2, 'prenom' => 'Paul', 'nom' => 'Durand', 'type' => 'Congé maladie', 'date_debut_fmt' => '12/05/2026', 'date_fin_fmt' => '14/05/2026'],
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

        return view('rh/demandes', [
            'user' => $this->getTestUser(),
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
}
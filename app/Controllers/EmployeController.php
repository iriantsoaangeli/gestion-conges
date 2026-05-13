<?php

namespace App\Controllers;

class EmployeController extends BaseController
{
    private function getTestUser()
    {
        return [
            'id' => 1,
            'prenom' => 'Jean',
            'nom' => 'Dupont',
            'email' => 'employe@techmada.mg',
            'departement' => 'Informatique',
            'date_embauche_fmt' => '15/01/2022',
        ];
    }

    public function dashboard()
    {
        return view('employe/dashboard', [
            'user' => $this->getTestUser(),
            'solde_total' => 30,
            'solde_annuel' => 25,
            'nb_attente' => 2,
            'nb_approuvees' => 8,
            'nb_refusees' => 1,
            'soldes' => [
                ['type' => 'Congé annuel', 'solde' => 25, 'pris' => 10, 'pct' => 40],
                ['type' => 'Congé maladie', 'solde' => 5, 'pris' => 0, 'pct' => 0],
            ],
            'dernieres_demandes' => [
                ['id' => 1, 'type' => 'Congé annuel', 'statut' => 'en_attente', 'date_debut_fmt' => '15/05/2026', 'date_fin_fmt' => '20/05/2026'],
                ['id' => 2, 'type' => 'Congé maladie', 'statut' => 'approuvee', 'date_debut_fmt' => '10/05/2026', 'date_fin_fmt' => '12/05/2026'],
            ],
        ]);
    }

    public function conges()
    {
        return view('employe/conge_index', [
            'user' => $this->getTestUser(),
            'badge_attente' => 2,
            'demandes' => [
                ['id' => 1, 'type' => 'Congé annuel', 'statut' => 'en_attente', 'date_debut_fmt' => '15/05/2026', 'date_fin_fmt' => '20/05/2026', 'nb_jours' => 5],
                ['id' => 2, 'type' => 'Congé maladie', 'statut' => 'approuvee', 'date_debut_fmt' => '10/05/2026', 'date_fin_fmt' => '12/05/2026', 'nb_jours' => 2],
                ['id' => 3, 'type' => 'Congé non payé', 'statut' => 'refusee', 'date_debut_fmt' => '01/04/2026', 'date_fin_fmt' => '05/04/2026', 'nb_jours' => 4],
            ],
        ]);
    }

    public function congeCreate()
    {
        return view('employe/conge_create', [
            'user' => $this->getTestUser(),
            'soldes' => [
                ['type' => 'Congé annuel', 'solde' => 25, 'pris' => 10, 'pct' => 40],
                ['type' => 'Congé maladie', 'solde' => 5, 'pris' => 0, 'pct' => 0],
            ],
            'types_conge' => [
                ['id' => 1, 'label' => 'Congé annuel'],
                ['id' => 2, 'label' => 'Congé maladie'],
                ['id' => 3, 'label' => 'Congé non payé'],
            ],
        ]);
    }

    public function profil()
    {
        return view('employe/profil', [
            'user' => $this->getTestUser(),
        ]);
    }
}
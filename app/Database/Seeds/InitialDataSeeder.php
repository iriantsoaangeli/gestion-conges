<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Vide les tables d'abord (important!)
        $this->db->table('employes')->truncate();
        $this->db->table('roles')->truncate();
        $this->db->table('departements')->truncate();

        $data = [
            // Departements
            'departements' => [
                ['id' => 1, 'nom' => 'RH', 'description' => 'Ressources humaines'],
                ['id' => 2, 'nom' => 'IT', 'description' => 'Informatique et support'],
                ['id' => 3, 'nom' => 'Finance', 'description' => 'Comptabilite et budget'],
                ['id' => 4, 'nom' => 'Operations', 'description' => 'Operations internes'],
            ],
            
            // Roles
            'roles' => [
                ['id' => 1, 'nom' => 'admin'],
                ['id' => 2, 'nom' => 'rh'],
                ['id' => 3, 'nom' => 'employe'],
            ],
            
            // Employes with proper hashed passwords
            'employes' => [
                [
                    'id' => 1,
                    'nom' => 'Rakoto',
                    'prenom' => 'Jean',
                    'email' => 'jean.rakoto@example.com',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'role_id' => 1,
                    'departement_id' => 2,
                    'date_embauche' => '2022-04-15',
                    'actif' => true,
                ],
                [
                    'id' => 2,
                    'nom' => 'Rabe',
                    'prenom' => 'Mia',
                    'email' => 'mia.rabe@example.com',
                    'password' => password_hash('rh123', PASSWORD_DEFAULT),
                    'role_id' => 2,
                    'departement_id' => 1,
                    'date_embauche' => '2023-01-10',
                    'actif' => true,
                ],
                [
                    'id' => 3,
                    'nom' => 'Andry',
                    'prenom' => 'Koto',
                    'email' => 'koto.andry@example.com',
                    'password' => password_hash('emp123', PASSWORD_DEFAULT),
                    'role_id' => 3,
                    'departement_id' => 2,
                    'date_embauche' => '2023-06-05',
                    'actif' => true,
                ],
                [
                    'id' => 4,
                    'nom' => 'Lala',
                    'prenom' => 'Sara',
                    'email' => 'sara.lala@example.com',
                    'password' => password_hash('emp123', PASSWORD_DEFAULT),
                    'role_id' => 3,
                    'departement_id' => 3,
                    'date_embauche' => '2024-02-20',
                    'actif' => true,
                ],
            ],
        ];

        // Insert data
        $this->db->table('departements')->insertBatch($data['departements']);
        $this->db->table('roles')->insertBatch($data['roles']);
        $this->db->table('employes')->insertBatch($data['employes']);

        echo "✅ Données initiales seedées avec succès!\n";
        echo "   Admin: jean.rakoto@example.com / admin123\n";
        echo "   RH: mia.rabe@example.com / rh123\n";
        echo "   Employé: koto.andry@example.com / emp123\n";
    }
}


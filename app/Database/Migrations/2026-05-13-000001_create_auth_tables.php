<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthTables extends Migration
{
    public function up()
    {
        // Departements
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nom'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('departements', true);

        // Roles
        $this->forge->addField([
            'id'  => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('roles', true);

        // Employes
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nom'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'prenom'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true, 'null' => true],
            'password'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'role_id'         => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'departement_id'  => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'date_embauche'   => ['type' => 'DATE', 'null' => true],
            'actif'           => ['type' => 'BOOLEAN', 'default' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('departement_id', 'departements', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employes', true);
    }

    public function down()
    {
        $this->forge->dropTable('employes', true);
        $this->forge->dropTable('roles', true);
        $this->forge->dropTable('departements', true);
    }
}

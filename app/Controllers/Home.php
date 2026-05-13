<?php

namespace App\Controllers;

use App\Models\Conge;
use App\Models\Employe;
use App\Models\Role;
use App\Models\Solde;
use App\Models\StatusConge;
use App\Models\TypeConges;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function testdb()
    {
        $sections = [
            'Employe' => new Employe(),
            // 'Role' => new Role(),
            // 'TypeConges' => new TypeConges(),
            // 'Solde' => new Solde(),
            // 'StatusConge' => new StatusConge(),
            // 'Conge' => new Conge(),
        ];

        foreach ($sections as $title => $model) {
            echo "\n==================== {$title} ====================\n";
            var_dump($model->findAll());
        }

        return '';
    }
}

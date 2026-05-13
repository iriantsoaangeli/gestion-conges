<?php

namespace App\Models;
use CodeIgniter\Model;

class Solde extends Model {

    protected $table = 'soldes';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'employe_id',
        'type_conge_id',
        'annee',
        'jours_attribues',
        'jours_pris',
    ];
    
}
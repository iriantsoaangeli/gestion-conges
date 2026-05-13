<?php

namespace App\Models;
use CodeIgniter\Model;

class Conge extends Model {

    protected $table = 'conges';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'employe_id',
        'type_conge_id',
        'status_id',
        'nb_jours',
        'date_debut',
        'date_fin',
        'commentaire_rh',
        'motif' ,
        'traite_par',
        'created_at',
        ];
    
}
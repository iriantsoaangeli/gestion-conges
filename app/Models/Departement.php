<?php


namespace App\Models;
use CodeIgniter\Model;

class Departement extends Model
{

    protected $table = 'departements';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'nom',
    ];

}
<?php

namespace App\Models;
use CodeIgniter\Model;

class TypeConges extends Model {

    protected $table = 'types_conges';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $allowedFields = [
        'id',
        'nom',
        'deductible',
    ];
}
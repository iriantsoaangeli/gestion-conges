<?php

namespace App\Models;
use CodeIgniter\Model;

class StatusConge extends Model
{
    protected $table = 'status_conges';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'nom',
    ];

}


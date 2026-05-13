<?php

namespace App\Models;

use CodeIgniter\Model;

class Employe extends Model
{
	protected $table = 'employes';
	protected $primaryKey = 'id';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;

	protected $allowedFields = [
		'nom',
		'prenom',
		'email',
		'role_id',
		'departement_id',
		'date_embauche',
        'password',
		'actif',
	];

	protected $useTimestamps = false;
	protected $protectFields = false;

	protected string $viewTable = 'v_employes';

	public function findView(int $id): ?array
	{
		return $this->db->table($this->viewTable)
			->where('id', $id)
			->get()
			->getRowArray();
	}

	public function findAllView(): array
	{
		return $this->db->table($this->viewTable)
			->get()
			->getResultArray();
	}
}
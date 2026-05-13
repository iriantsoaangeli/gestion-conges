<?

namespace App\Models ;
use CodeIgniter\Model;

class Role extends Model {

    protected $table = 'roles';
	protected $primaryKey = 'id';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;

	protected $allowedFields = [
		'id',
		'nom',
	];
    
}
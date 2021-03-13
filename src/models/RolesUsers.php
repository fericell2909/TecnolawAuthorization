<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RolesUsers extends Model
{
	use SoftDeletes;

	protected $table = 'tecnolaw_roles_users';

	protected $fillable = [
		'user_id',
		'assigned_by',
		'removed_by',
		'role_id',
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	
	public function user()
	{
		return $this->belongsTo(User::class,'created_by');
	}
}
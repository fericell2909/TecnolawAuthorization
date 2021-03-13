<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{
	use SoftDeletes;

	protected $table = 'tecnolaw_roles';

	protected $fillable = [
		'name',
		'back_office', 
		'created_by',
		'status',
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
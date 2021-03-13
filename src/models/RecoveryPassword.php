<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RecoveryPassword extends Model
{
	use SoftDeletes;

	protected $table = 'tecnolaw_recovery_password';

    protected $fillable = [
		'id',
		'user_id',
		'token',
		'user_agent',
		'expiration_date',
		'email_send',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
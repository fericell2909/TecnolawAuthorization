<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Token extends Model
{
	use SoftDeletes;

	protected $table = 'tecnolaw_tokens';

    protected $fillable = [
		'user_id',
		'token',
		'user_agent',
		'browser',
		'ip',
		'device',
		'os',
		'external_token',
		'expiration_date',
		'back_office',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
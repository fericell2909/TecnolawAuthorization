<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthenticateWith extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'tecnolaw_authenticate_with';

    protected $fillable = [
        'display_name',
        'slug',
        'client_id',
        'key_secret',
        'redirect_uri',
        'enabled',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addresses extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'tecnolaw_addresses';

    protected $fillable = [
		'user_id',
		'commune_id',
		'region_id',
		'street',
		'number',
		'description',
		'department_number',
		'lat',
		'log',
		'postal_code',
        'main_default',
        'dpto_home_office_aditional',
        'type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
}

<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commune extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'tecnolaw_communes';

    protected $fillable = [
        'name',
		'region_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
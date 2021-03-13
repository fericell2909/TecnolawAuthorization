<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'tecnolaw_regions';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
<?php
namespace Tecnolaw\Authorization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'tecnolaw_phones';

    protected $fillable = [
		'user_id',
        'code',
		'number',
		'type', // 1 = casa, 2 = trabajo, 3 = otros
		'main_default',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php
namespace Tecnolaw\Authorization\Models;

use App\Models\Answer;
use App\Models\Enrollment;
use Tecnolaw\Authorization\Models\Token;
use Ecommerce\Checkout\Models\Contact;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Ecommerce\Checkout\Models\DeliveryAddress;


class User extends Model implements AuthenticatableContract, AuthorizableContract
{
	use Authenticatable, Authorizable, SoftDeletes;
	protected $table = 'tecnolaw_users';

	protected $fillable = [
		'status',
		'rut',
		'name',
		'paternal_surname',
		'maternal_surname',
		'main_phone',
		'office_phone',
		'home_phone',
		'username',
		'email',
		'second_email',
		'language',
		'gender',
		'birth_date',
		'nationality',
		'password',
		'fb_id',
        'fb_access_token',
        'notifications'
	];

	protected $hidden = [
		'password',
	];
	protected $appends = ['full_name'];

	public function setRutAttribute($value)
	{
		$this->attributes['rut'] = str_replace('.', '', $value);
	}
	public function setEmailAttribute($value)
    {
    	$email = $value;
    	if (!empty($value)) {
	    	$this->attributes['email']=$email;

	    	$username=explode('@', $email)[0];
	        $usernameExits=User::where('username',$username)->count();
	        if($usernameExits>0){
	        	$last_id = app('db')->select("SELECT id FROM ".$this->getTable()." ORDER BY id DESC LIMIT 1")[0]->id+1;
	        	$username = $username.'_'.$last_id;
	        }

	        $this->attributes['username']=$username;
    	}

    }
	public function getRutAttribute($value)
	{
		return str_replace('.', '', $value);
	}
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = hashPass($value);
	}
	public function getFullNameAttribute($value)
	{
		$full_name= $this->name . ' ';
		$full_name.= $this->paternal_surname . ' ';
		$full_name.= $this->maternal_surname;
		if (strlen(trim(preg_replace('/\xc2\xa0/',' ',$full_name))) == 0) {
			return $this->username;
		}else{
			return $full_name;
		}

    }

    public function direcciones()
    {
    	return $this->hasMany(DeliveryAddress::class, 'user_id');
    }

    public function phone()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    public function tokens()
    {
    	return $this->hasMany(Token::class, 'user_id');
    }

    public function answers()
    {
    	return $this->belongsToMany(Answer::class, 'user_answer');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,(new RolesUsers)->getTable())
            ->withPivot(
                'user_id',
				'assigned_by',
				'removed_by',
				'role_id',
            );
    }

    public function addresses()
    {
    	return $this->hasMany(Addresses::class, 'user_id');
    }
    public function phones()
    {
    	return $this->hasMany(Phone::class, 'user_id');
    }

    public function addressMain()
    {
    	return $this->hasMany(Addresses::class, 'user_id')->where('main_default',1)->first();
    }
    public function phoneMain()
    {
    	return $this->hasMany(Phone::class, 'user_id')->where('main_default',1)->first();
    }
}

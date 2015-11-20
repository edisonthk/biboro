<?php namespace Biboro\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Account extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

	protected $table = 'accounts';

    protected $hidden = array('google_id','password', 'remember_token','authorization_code');

    protected $fillable = array('email','name','locate','lang','level','password');
	
	public $timestamps = true;

}

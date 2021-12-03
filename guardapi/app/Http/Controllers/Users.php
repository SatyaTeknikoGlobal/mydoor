<?php


namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Zizaco\Entrust\Traits\EntrustUserTrait;

class Users extends Authenticatable{
	
	use Notifiable;

	protected $table = 'users';

	protected $guard = 'appusers';

	protected $guarded = [];  

	protected $fillable = [];

	protected $hidden = [
		'password', 'remember_token',
	];


}
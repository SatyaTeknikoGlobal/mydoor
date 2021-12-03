<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    //
    use Notifiable;

    protected $guard = 'merchant';
    
    protected $table = 'vendors';

    protected $fillable = [
        'email', 'password','business_name','phone','state_id','city_id','status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
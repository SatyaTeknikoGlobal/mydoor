<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Zizaco\Entrust\Traits\EntrustUserTrait;

class SocietyAdmin extends Authenticatable{

    use Notifiable;

    protected $guard = 'sadmin';

    protected $table = 'societyadmins';

    protected $guarded = ['id'];    

    protected $fillable = [
       
        'name',
        'username',
        'email',
        'password',
        'phone',
        'address',
        'society_id',
        'state_id',
        'city_id',
    ];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    
    protected $table = 'orders';

    protected $guarded = ['id'];

     public $timestamps = false;

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    
    protected $table = 'products';

    protected $guarded = ['id'];

     public $timestamps = false;

}
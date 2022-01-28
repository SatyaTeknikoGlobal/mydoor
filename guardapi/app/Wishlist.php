<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model{
    
    protected $table = 'wishlists';

    protected $guarded = ['id'];

     public $timestamps = false;

}
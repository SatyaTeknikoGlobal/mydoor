<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model{
    
    protected $table = 'subcategories';

    protected $guarded = ['id'];

    protected $fillable = [];
}
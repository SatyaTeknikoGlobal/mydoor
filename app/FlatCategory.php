<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class FlatCategory extends Model{

    protected $table = 'flat_categories';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];

}
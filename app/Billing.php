<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Billing extends Model{

    use HasFactory;

    protected $table = 'billings';
  
    protected $fillable = [];


}
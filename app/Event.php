<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Event extends Model{

    use HasFactory;

    protected $table = 'events';
  
    protected $fillable = [
        'title', 'start', 'end'
    ];


}
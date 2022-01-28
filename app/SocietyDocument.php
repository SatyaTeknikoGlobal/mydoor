<?php
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class SocietyDocument extends Model{
    
    protected $table = 'society_documents';

    protected $guarded = ['id'];

    protected $fillable = [];


 
}
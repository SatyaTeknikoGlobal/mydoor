<?php
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class ServiceUser extends Model{
    protected $table = 'service_users';

    protected $guarded = ['id'];

}
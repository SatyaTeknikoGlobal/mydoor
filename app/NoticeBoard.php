<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class NoticeBoard extends Model{


    protected $table = 'notice_boards';
  
    protected $fillable = ['notice_id'];




    public function getDocuments(){
        return $this->hasMany('App\NoticeBoardDocument', 'notice_id');
    }
}
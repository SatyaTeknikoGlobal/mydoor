<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class NoticeBoardDocument extends Model{


    protected $table = 'notice_board_documents';
  
    protected $fillable = ['notice_id','file','type','status'];


}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_regular_user extends Model
{
    use HasFactory;

    protected $table = 'course_regular_user';
    protected $fillable = [

         'id_primary',
         'id_course_regular',
         'id_shop_article',
         'id_user'
    
    ];



}

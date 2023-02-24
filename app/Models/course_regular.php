<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_regular extends Model
{
    use HasFactory;

    protected $table = 'course_regular';
    protected $fillable = [
        'id_course_regular',
        'id_shop_article',
        'id_user',
        'multi_user'
    
    ];



}

  

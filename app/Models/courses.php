<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    use HasFactory;


    protected $table = 'courses';
    protected $fillable = [
        'id_course',
        'id_course_regular',
        'id_course_occasional',
        'start_date',
        'end_date',
        'day',
        'id_room'
    ];




}

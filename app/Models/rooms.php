<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rooms extends Model
{
    use HasFactory;



   
    protected $table = 'rooms';
    protected $fillable = [
        'id_room',
        'id_name',
        'address',
        'map',
        'url_room',
        'room_active'
    ];





}

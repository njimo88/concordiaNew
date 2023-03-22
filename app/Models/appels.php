<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appels extends Model
{
    use HasFactory;

    protected $table = 'appels';
    protected $primaryKey = 'id';
   
    

    protected $fillable = [
    
            'id_cours',
            'date',
            'presents',
            'created_at',
            'updated_at'
    ];

   







}

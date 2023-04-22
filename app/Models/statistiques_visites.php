<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class statistiques_visites extends Model
{
    use HasFactory;
    protected $table = 'statistiques_visites';
    protected $primaryKey = 'id';
    
   

    protected $fillable = [
        'id',
        'page',
        'nbre_visitors',
        'annee',
        'updated_at'
    ];
}

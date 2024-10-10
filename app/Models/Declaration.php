<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;

    

    protected $table = 'declarations';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'annee',
        'mois',
        'heures_realisees', 
        'jours_conges',    
        'jours_maladie',
        'details',
        'valider',
        'soumis',
    ];

    protected $casts = [
        'details' => 'array'
    ];

    
}

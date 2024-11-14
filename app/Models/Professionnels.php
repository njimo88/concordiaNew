<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Professionnels extends Model
{
    use HasFactory;
    protected $table = 'users_professionals';
    public $timestamps = false;
    
    protected $primaryKey = 'cle';

    protected $fillable = [
        'id_user',
        'lastname',
        'firstname',
        'matricule',
        'VolumeHebdo',
        'SoldeConges',
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
        'Dimanche',
        'OldHeuresRealisees',
        'Groupe',
        'Embauche',
        'Salaire',
        'Prime',
        'masque',
        'LastDeclarationMonth',
        'LastDeclarationYear',
        'email',
        'Saison',
        'updated_at',
    ];
}



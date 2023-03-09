<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class A_Categorie1 extends Model
{
    use HasFactory;

    protected $table = 'categorie1';
    protected $primaryKey = 'Id_categorie1';
    public $incrementing = false;
    

    protected $fillable = [
       
        'nom_categorie',
        'image',
        'categorie_URL',
        'description',
        'visibilite',
        'updated_at'
    ];

   







}

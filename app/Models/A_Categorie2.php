<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class A_Categorie2 extends Model
{
    use HasFactory;

    protected $table = 'categorie2';
    protected $primaryKey = 'Id_categorie2';
    public $incrementing = false;
    

    protected $fillable = [
       
        'nom_categorie',
        'description',
         'categorie_URL',
         'image',
        'updated_at'
    ];

   







}

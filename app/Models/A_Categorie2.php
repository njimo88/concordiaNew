<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class A_Categorie2 extends Model
{
    use HasFactory;

    
    protected $table = 'categorie2';
    protected $primarykey = 'Id_categorie2';

    protected $fillable = [
    'Id_categorie2 ',
    'nom_categorie',
   'description',
    'categorie_URL',
    'image',
    ];


}

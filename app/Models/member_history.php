<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member_history extends Model
{
    use HasFactory;

    protected $table = 'member_history';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_user',
        'nom',
        'prenom',
        'date_naissance',
        'saison'
    ];

}



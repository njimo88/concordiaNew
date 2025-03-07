<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacance extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'vacances';
    protected $fillable = [
        'annee',
        'toussaintDebut',
        'toussaintFin',
        'noelDebut',
        'noelFin',
        'hiverDebut',
        'hiverFin',
        'printempsDebut',
        'printempsFin',
        'eteDebut',
        'eteFin',
    ];
}

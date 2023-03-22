<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementImmediat extends Model
{
    use HasFactory;

    protected $table = 'paiement_immediat';
    public $timestamps = false;
    

    protected $fillable = [
        'bill_id',
        'user_id',
        'family_id',
    ];
}

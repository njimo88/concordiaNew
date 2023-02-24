<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class bills extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_bill',
        'type',
        'status',
        'payment_total_amount',
        'payment_method',
        'user_id',
        'family_id',
        'ref',
        'total_charges'
    ];

    
}
 
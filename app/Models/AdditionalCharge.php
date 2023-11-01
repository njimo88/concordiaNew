<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalCharge extends Model
{
    use HasFactory;

    protected $table = 'additional_charges';

    protected $fillable = [
        'bill_id',
        'family_id',
        'amount',
    ];

    // Define the relationship with the Bill model
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    // Define the created_at and updated_at timestamps (if you want to use them)
    public $timestamps = true;
}

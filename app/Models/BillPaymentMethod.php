<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPaymentMethod extends Model
{
    protected $table = 'bills_payment_method';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'payment_method',
        'image',
        'icon',
        'text',
        'charges',
    ];

    // Define the relationship with bills here if necessary
    // For example, if a payment method can belong to many bills
    public function bills()
    {
        return $this->hasMany(bills::class, 'payment_method');
    }
}

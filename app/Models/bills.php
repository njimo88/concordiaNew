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
        'total_charges',
        'amount_paid',
        'number',

    ];
    public function getImageAttribute()
    {
        return  $this->paymentMethod->icon; 
    }

    public function getImageStatusAttribute()
    {
        return $this->Billstat->image_status;
    }


    public function Billstat()
    {
        return $this->belongsTo(BillStatus::class, 'status');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(BillPaymentMethod::class, 'payment_method');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // If you have a relationship with additional charges, define it here
    public function additionalCharges()
    {
        return $this->hasMany(AdditionalCharge::class, 'bill_id');
    }

    
}
 
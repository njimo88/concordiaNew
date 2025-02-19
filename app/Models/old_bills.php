<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class old_bills extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', // Ensure 'id' is fillable
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

    // Relationships
    public function getImageAttribute()
    {
        if ($this->paymentMethod && $this->paymentMethod->icon) {
            return $this->paymentMethod->icon;
        }

        return BillPaymentMethod::where('id', 1)->value('image');
    }

    public function getImageStatusAttribute()
    {
        if ($this->Billstat && $this->Billstat->image_status) {
            return $this->Billstat->image_status;
        }

        return BillStatus::where('id', 100)->value('image_status');
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

    public function additionalCharges()
    {
        return $this->hasMany(AdditionalCharge::class, 'bill_id');
    }
}

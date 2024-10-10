<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillStatus extends Model
{
    protected $table = 'bills_status';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'image_status',
        'row_color',
        'mail_content',
    ];

    // Define the relationship with bills here if necessary
    // For example, if a status can belong to many bills
    public function bills()
    {
        return $this->hasMany(bills::class, 'status');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $table = 'maintenances';

    protected $fillable = [
        'ip_address',
    ];

    /**
     * Get the user that owns the maintenance record.
     */
    
}

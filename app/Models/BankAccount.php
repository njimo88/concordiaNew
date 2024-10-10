<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_accounts'; 
    protected $fillable = [
        'name', 'site_id', 'secret_key', 'api_info'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopMessage extends Model
{
    use HasFactory;

    protected $table = 'shop_messages';
    public $timestamps = false;
    protected $primaryKey = 'id_shop_message';


    protected $fillable = [
        'message',
        'date',
        'id_bill',
        'id_customer',
        'id_admin',
        'state',
        'somme_payé', 
    ];
}

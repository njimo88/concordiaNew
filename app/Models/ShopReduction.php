<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReduction extends Model
{
    use HasFactory;

    protected $table = 'shop_reductions';
    protected $primaryKey = 'id_shop_reduction';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'percentage',
        'value',
        'description',
        'usable',
        'state',
        'startvalidity',
        'endvalidity',
        'destroy',
        'automatic',
        'image'
    ];


}

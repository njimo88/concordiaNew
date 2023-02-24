<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop_reduction extends Model
{
    use HasFactory;


    protected $table = 'shop_reduction';
    protected $primarykey = 'id_shop_reduction';

    protected $fillable = [
        'code',
        'percentage',
        'value',
        'description',
        'id_shop_reduction',
        'max_per_user',
        'active',
        'startvalidity',
        'endvalidity',
        'automatic',
        'image',
        'limited_user',
        'limited_shop_article',
        

    ];







}

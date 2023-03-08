<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop_category extends Model
{
    use HasFactory;

    protected $table = 'shop_category';
    protected $primaryKey = 'id_shop_category';
    public $incrementing = false;
    

    protected $fillable = [
       
        'name',
        'image',
        'description',
        'id_shop_category_parent',
        'url_shop_category',
        'order_category',
        'active'
    ];

   







}

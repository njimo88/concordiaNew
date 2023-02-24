<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop_category extends Model
{
    use HasFactory;

      
    protected $table = 'shop_category';
    protected $primarykey = 'id_shop_category';
    public $timestamps = false;

    protected $fillable = [
    'id_shop_category',
    'name',
    'image',
    'description',
    'id_shop_category_parent',
    'url_shop_category',
    'order_category',
    'active' 

    ];

    public function categories()
{
        return $this->hasMany(Shop_category::class,'id_shop_category_parent','id_shop_category')->orderBy('order_category', 'ASC');
}


}

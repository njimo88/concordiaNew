<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop_article extends Model
{
    use HasFactory;

    protected $table = 'shop_article';
    protected $primaryKey = 'id_shop_article';
    public $incrementing = false;
    

    protected $fillable = [
       
            'title',
            'ref',
           
            'saison',
            'type_article',
            'stock_ini',
            'stock_actuel',
            'price',
            'totalprice',
            
            'price_indicative',
            'description',
            'short_description',
            'image',
            'url_shop_article', 
            'startvalidity',
            'endvalidity',
            'agemin',            
            'agemax',
            'max_per_user',
            'need_member',
            'alert_stock',
            'afiscale',
            'sex_limit',
            'nouveaute',
            'selected_limit',
            'buyers',
            'categories',
            'created_at',
            'updated_at'
    ];

   







}

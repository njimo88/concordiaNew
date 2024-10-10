<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop_article;
use App\Models\ShopReduction;

class LiaisonShopArticlesShopReductions extends Model
{
    use HasFactory;

    protected $table = 'liaison_shop_articles_shop_reductions';
    protected $primaryKey = 'id_liaison';
    public $timestamps = false;

    protected $fillable = [
        'id_shop_article',
        'id_shop_reduction'
    ];


}

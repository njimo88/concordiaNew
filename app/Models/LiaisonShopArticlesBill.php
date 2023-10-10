<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiaisonShopArticlesBill extends Model
{
    use HasFactory;
    
    protected $table = 'liaison_shop_articles_bills';
    protected $primaryKey = 'id_liaison';
    public $timestamps = false;

    protected $fillable = [
        'bill_id',
        'href_product',
        'quantity',
        'ttc',
        'addressee',
        'sub_total',
        'designation',
        'certificate',
        'declinaison',
        'id_shop_article',
        'id_user',
    ];


    public function shopArticle()
{
    return $this->belongsTo(Shop_article::class, 'id_shop_article');
}

}

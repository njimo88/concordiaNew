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
        'is_prepared',
        'is_distributed',
        'is_returned'
    ];


    public function shopArticle()
{
    return $this->belongsTo(Shop_article::class, 'id_shop_article');
}

public function bill()
    {
        return $this->belongsTo(bills::class, 'bill_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function preparationConfirmation()
    {
        return $this->hasOne(ArticlePreparationConfirmation::class, 'liaison_shop_article_bill_id', 'id_liaison');
    }

    public function distributionDetail()
    {
        return $this->hasOne(DistributionDetail::class, 'liaison_shop_article_bill_id', 'id_liaison');
    }
    public function productReturn()
    {
        return $this->hasOne(ProductReturn::class, 'liaison_shop_article_bill_id', 'id_liaison');
    }
}

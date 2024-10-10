<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    protected $table = 'product_returns';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'liaison_shop_article_bill_id',
        'returned_by_user_id',
        'reason',
        'returned_at'
    ];

    protected $dates = ['returned_at'];

    public function liaisonShopArticlesBill()
    {
        return $this->belongsTo(LiaisonShopArticlesBill::class, 'liaison_shop_article_bill_id', 'id_liaison');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'returned_by_user_id', 'user_id');
    }
}

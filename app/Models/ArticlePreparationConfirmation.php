<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlePreparationConfirmation extends Model
{

   
    public $timestamps = false;

    protected $fillable = [
        'liaison_shop_article_bill_id', 
        'confirmed_by_user_id', 
        'confirmed_at'
    ];

    protected $dates = ['confirmed_at'];


    public function liaisonShopArticlesBill()
    {
        return $this->belongsTo(LiaisonShopArticlesBill::class, 'liaison_shop_article_bill_id', 'id_liaison');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'confirmed_by_user_id', 'user_id');
    }
}

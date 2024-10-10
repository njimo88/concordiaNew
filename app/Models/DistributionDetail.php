<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionDetail extends Model
{
    protected $table = 'distribution_details';
    public $timestamps = false;


    protected $fillable = [
        'liaison_shop_article_bill_id', 
        'distributed_by_user_id', 
        'distributed_at'
    ];

    protected $dates = ['distributed_at'];

    public function liaisonShopArticlesBill()
    {
        return $this->belongsTo(LiaisonShopArticlesBill::class, 'liaison_shop_article_bill_id', 'id_liaison');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'distributed_by_user_id', 'user_id');
    }
}

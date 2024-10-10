<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReductionUsage extends Model
{
    // Table Name
    protected $table = 'user_reduction_usage';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = false;

    // Define the fillable properties
    protected $fillable = ['user_id', 'reduction_id', 'shop_article_id', 'usage_max', 'usage_count'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shopReduction()
    {
        return $this->belongsTo(ShopReduction::class, 'reduction_id');
    }

    public function shopArticle()
    {
        return $this->belongsTo(ShopArticle::class, 'shop_article_id');
    }
}

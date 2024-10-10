<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declinaison extends Model
{
    use HasFactory;

    protected $table = 'declinaisons';

    protected $fillable = [
        'shop_article_id',
        'libelle',
        'stock_ini_d',
        'stock_actuel_d',
    ];

    public $timestamps = true;

    public function shopArticle()
    {
        return $this->belongsTo(Shop_article::class, 'shop_article_id', 'id_shop_article');
    }
}

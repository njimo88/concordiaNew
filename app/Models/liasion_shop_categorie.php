<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class liasion_shop_categorie extends Model
{
    use HasFactory;

    protected $table = 'liaison_shop_articles_shop_categories';
    
    

    protected $fillable = [
        'id_shop_article',
        'id_shop_category',
        'updated_at',
        'created_at'
    ];

       
}

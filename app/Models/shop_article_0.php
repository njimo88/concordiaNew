<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_article_0 extends Model
{
    use HasFactory;
    protected $table = 'shop_article_0';
    protected $primarykey = 'id_shop_article';

    protected $fillable = [
        'id_shop_article',
        'prix_adhesion',
        'prix_assurance',
        'prix_licence_fede',
        'updated_at',
        'created_at'
        			


    ];

}

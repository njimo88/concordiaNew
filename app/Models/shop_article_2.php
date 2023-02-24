<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_article_2 extends Model
{
    use HasFactory;

    protected $table = 'shop_article_2';
    protected $primarykey = 'id_shop_article';

    protected $fillable = [

        'id_shop_article',
        'declinaison',
        'updated_at',
        'created_at'
        		
    ];


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_article_1 extends Model
{
    use HasFactory;

    protected $table = 'shop_article_1';
    protected $primaryKey = 'id_shop_article';
    public $incrementing = false;
    

    protected $fillable = [  
            'stock_ini',
            'stock_actuel',
            'teacher',
            'lesson',
            'created_at',
            'updated_at'
    ];

   







}

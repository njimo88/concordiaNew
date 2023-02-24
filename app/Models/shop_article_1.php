<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_article_1 extends Model
{
    use HasFactory;



    protected $table = 'shop_article_1';
    protected $primarykey = 'id_shop_article';

    protected $fillable = [
        'id_shop_article',
        'stock_ini',
        'stock_actuel',
        'teacher',
        'lesson',
        'updated_at',
        'created_at'

    ];












}

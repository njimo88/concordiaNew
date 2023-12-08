<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop_service extends Model
{
    use HasFactory;



    protected $table = 'shop_service';
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_article_2 extends Model
{
    use HasFactory;

    protected $table = 'shop_article_2';
    protected $primaryKey = 'id_shop_article';
    public $incrementing = false;
    

    protected $fillable = [
       
            'declinaison',
            'created_at',
            'updated_at'
    ];

   







}

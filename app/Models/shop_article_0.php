<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_article_0 extends Model
{
    use HasFactory;

    protected $table = 'shop_article_0';
    protected $primaryKey = 'id_shop_article';
    public $incrementing = false;
    

    protected $fillable = [
        'prix_adhesion',
        'prix_assurance',
        'prix_licence_fede',
        'updated_at',
        'created_at'
        	
    ];

   







}

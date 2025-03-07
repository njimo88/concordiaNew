<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Basket extends Model
{
    use HasFactory;

    protected $table = 'basket';
    protected $fillable = ['user_id', 'family_id', 'pour_user_id', 'ref', 'qte','declinaison', 'prix','reduction'];

public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shopArticle()
    {
        return $this->belongsTo(Shop_article::class, 'ref', 'id_shop_article');
    }
}

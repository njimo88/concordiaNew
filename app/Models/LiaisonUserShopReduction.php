<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiaisonUserShopReduction extends Model
{
    use HasFactory;

    protected $table = 'liaison_user_reductions';
    protected $primaryKey = 'id_liaison';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'id_shop_reduction'
        
    ];
}

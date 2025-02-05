<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimationsCategories extends Model
{
    use HasFactory;

    protected $table = 'animations_categories';

    protected $fillable = [
        'name',
        'color',
        'text_color',
    ];

    public $timestamps = false;

    public function animations()
    {
        return $this->hasMany(Animation::class);
    }
}

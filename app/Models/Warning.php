<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    use HasFactory;

    protected $table = 'warnings';

    public $timestamps = true;

    protected $fillable = [
        'family_id',
        'level',
        'created_by',
        'updated_by'
    ];

    public function family()
    {
        return $this->belongsToMany(User::class,);
    }

    public static function booted()
    {
        static::creating(function (self $warning) {
            $warning->updated_at = null;
            $warning->updated_by = null;
        });
    }
}

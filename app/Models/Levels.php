<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Levels extends Model
{
    use HasFactory;

    protected $table = 'levels';

    protected $fillable = ['name', 'color'];

    public $timestamps = false;

    public function userLevels()
    {
        return $this->hasMany(UsersLevels::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplines extends Model
{
    use HasFactory;

    protected $table = 'disciplines';

    protected $fillable = ['name'];

    public $timestamps = false;

    public function userLevels()
    {
        return $this->hasMany(UsersLevels::class);
    }
}
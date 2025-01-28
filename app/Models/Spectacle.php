<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spectacle extends Model
{
    protected $primaryKey = 'id_spectacle';
    use HasFactory;
    protected $fillable = ['name','description','total_seats','state','date','image'];
    
    public function seats()
    {
        return $this->hasMany(Seat::class, 'id_spectacle', 'id_spectacle');
    }
}

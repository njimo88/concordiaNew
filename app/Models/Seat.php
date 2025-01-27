<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_seat';
    protected $fillable = ['available', 'seat_number','state'];

    public function spectacle()
    {
        return $this->belongsTo(Spectacle::class, 'id_spectacle', 'id_spectacle');
    }
}

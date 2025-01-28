<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_reservation';
    protected $fillable = ['reservation_date', 'status','id_seat','id_user'];

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'id_seat', 'id_seat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}

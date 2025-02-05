<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimationsRegistrations extends Model
{
    use HasFactory;

    protected $table = 'animation_registrations';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'age',
        'emergency_contact',
        'unsubscribe_token',
        'animation_id',
    ];

    public $timestamps = false;

    public function animation()
    {
        return $this->belongsTo(Animation::class, 'animation_id');
    }
}

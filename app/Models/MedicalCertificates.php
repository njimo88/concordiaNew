<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCertificates extends Model
{
    use HasFactory;

    protected $table = 'medical_certificates';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $attributes = [
        'validated' => 1
    ];

    protected $fillable = [
        'user_id',
        'expiration_date',
        'file_path',
        'validated'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersLevels extends Model
{
    use HasFactory;

    protected $table = 'users_levels';

    protected $fillable = ['user_id', 'discipline_id', 'level_id', 'exam_date', 'points', 'created_by', 'updated_by'];

    public $timestamps = true;

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->updated_at = null;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Disciplines::class);
    }

    public function level()
    {
        return $this->belongsTo(Levels::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

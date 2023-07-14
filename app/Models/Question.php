<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    
    protected $fillable = ['id', 'intitule'];

    public $incrementing = false;
    protected $keyType = 'string';
    
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }
}

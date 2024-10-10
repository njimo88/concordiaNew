<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'reponses';

    protected $fillable = ['id', 'question_id', 'intitule', 'image', 'lien'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function nextQuestion()
    {
        return $this->belongsTo(Question::class, 'lien', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'lien', 'id');
    }
}
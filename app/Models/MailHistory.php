<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailHistory extends Model
{
    use HasFactory;
    protected $table = 'mail_history';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user_expediteur',
        'title',
        'message',
        'link_pj',
        'date',
        'id_user_destinataires'
    ];

    public function sender()
{
    return $this->belongsTo(User::class, 'id_user_expediteur', 'user_id');
}

public function getSenderFullNameAttribute()
{
    return "{$this->sender->lastname} {$this->sender->name}"; 
}


}

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

}

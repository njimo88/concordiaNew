<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{
    use HasFactory;
    protected $table = 'email_queue';

    protected $fillable = [
        'recipient',
        'recipientName',
        'subject',
        'content',
        'sender',
        'fromName',
        'senderName', 
        'status',
        'attachments',
    ];
    
}

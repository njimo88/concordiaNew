<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeclarationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $attachment;
    public $attachmentName;
    public $emailContent; 
    public $emailSubject;

    public function __construct($emailContent, $attachment, $attachmentName, $username, $emailSubject) 
    {
        $this->emailContent = $emailContent; 
        $this->username = $username;
        $this->attachment = $attachment;
        $this->attachmentName = $attachmentName;
        $this->emailSubject = $emailSubject;
    }

    public function build()
    {
        $email = $this->subject($this->emailSubject)
            ->view('emails.declaration')
            ->attachData(base64_decode($this->attachment), $this->attachmentName, [
                'mime' => 'application/pdf',
            ]);

        return $email;
    }
}

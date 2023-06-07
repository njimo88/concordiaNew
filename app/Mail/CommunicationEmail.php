<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommunicationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $firstName;
    public $lastName;
    public $content;
    public $senderName;
    public $subject;
    public $email; 

    public function __construct($firstName, $lastName, $content, $senderName, $subject) 
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->content = $content;
        $this->senderName = $senderName;
        $this->subject = $subject;
    }

    public function build()
{
    return $this->from(config('mail.from.address'), config('mail.from.name'))
        ->subject($this->subject)
        ->view('emails.communicationTemplate');
}

    
}

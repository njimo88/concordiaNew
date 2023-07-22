<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class CommunicationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $content;
    public $senderName;
    public $subject;
    public $email; 
    public $username;

    public function __construct($name, $content, $senderName, $subject, $senderEmail, $fromName, $username)
    {
        $this->name = $name;
        $this->content = $content;
        $this->senderName = $senderName;
        $this->subject = $subject;
        $this->senderEmail = $senderEmail; 
        $this->fromName = $fromName; 
        $this->username = $username; 
    }
    


    public function build()
    {
        return $this->from($this->senderEmail, $this->fromName)
            ->subject($this->subject)
            ->view('emails.communicationTemplate');
    }
    

    
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $content_Email;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->userName = $data['name'];
        $this->userEmail = $data['email'];
        $this->content_Email = $data['message'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('president@gym-concordia.com', 'Gym-Concordia.com') 
                    ->replyTo($this->userEmail, $this->userName)  
                    ->subject('['.$this->userName.'] Message d\'un utilisateur')
                    ->view('Communication/form_email');
    }
}

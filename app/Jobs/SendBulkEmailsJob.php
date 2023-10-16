<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use App\Models\User;
use App\Mail\CommunicationEmail;
use Mail;


class SendBulkEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emails;
    protected $subject;
    protected $content;
    protected $senderName;
    protected $validator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emails, $subject, $content, $senderName, $validator , $attachments = [])
    {
        $this->emails = $emails;
        $this->subject = $subject;
        $this->content = $content;
        $this->senderName = $senderName;
        $this->validator = $validator;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invalidEmails = [];

        foreach ($this->emails as $email) {
            if ($this->validator->isValid($email, new RFCValidation())) {
                $user = User::where('email', $email)->first();
                $firstName = $user->name;
                $lastName = $user->lastname;

                Mail::to($email)->queue(new CommunicationEmail($firstName, $lastName, $this->content, $this->senderName, $this->subject, $email, $this->attachments));

            } else {
                $invalidEmails[] = $email;
            }
        }
    }
}

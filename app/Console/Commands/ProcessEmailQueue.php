<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailQueue;
use App\Models\User;
use App\Mail\CommunicationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; 

class ProcessEmailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:email-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the email queue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    
    public function handle()
    {
        $emails = EmailQueue::where('status', 'pending')->take(10)->get();
        
        foreach ($emails as $email) {
            try {
                // Use the stored fromName, senderName, and recipientName from the email queue
                $fromName = $email->fromName;
                $senderName = $email->senderName;
                $recipientName = $email->recipientName;
    
                // If any of the required fields are null, log the issue and mark the email as failed
                if (is_null($fromName) || is_null($senderName) || is_null($recipientName)) {
                    $reason = 'Failed to send queued email due to null fields.';
                    Log::error($reason, [
                        'fromName' => $fromName,
                        'senderName' => $senderName,
                        'recipientName' => $recipientName,
                    ]);
                    $email->status = $reason; // set the reason as the status
                    $email->save();
                    continue; // Skip this iteration and move on to the next email
                }
    
                // Send the email using the CommunicationEmail mailable
                Mail::to($email->recipient)->send(new CommunicationEmail($recipientName, $email->content, $senderName, $email->subject, $email->sender, $fromName));
    
                // If email is sent successfully, delete it from the database
                $email->delete();
    
                // Pause for 6 seconds to throttle the rate of sending
                sleep(7);
    
            } catch (\Exception $e) {
                $reason = 'Failed to send queued email. Error: '.$e->getMessage();
                $email->status = $reason; // set the reason as the status
                Log::error($reason);
                $email->save();
            }
        }
    
        return 0;
    }
}

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
        $emails = EmailQueue::where('status', 'pending')->take(600)->get();
     
         foreach ($emails as $email) {
             try {
                 $fromName = $email->fromName;
                 $senderName = $email->senderName;
                 $recipientName = $email->recipientName;
                 $username = User::where('email', $email->recipient)->first()->username;
         
                 if (is_null($fromName) || is_null($senderName) || is_null($recipientName)) {
                     $reason = 'Failed to send queued email due to null fields.';
                     Log::error($reason, [
                         'fromName' => $fromName,
                         'senderName' => $senderName,
                         'recipientName' => $recipientName,
                     ]);
                     $email->status = $reason;
                     $email->save();
                     continue;
                 }
     
                 $attachmentsJson = json_decode($email->attachments, true);
                Mail::to($email->recipient)->send(new CommunicationEmail($recipientName, $email->content, $senderName, $email->subject, $email->sender, $fromName, $username, $attachmentsJson));

                 $email->delete();
         
                 usleep(100000);  // Wait 0.1 seconds before sending the next email
     
             } catch (\Exception $e) {
                 $reason = 'Failed to send queued email. Error: '.$e->getMessage();
                 Log::error($reason);
                 $email->status = $reason;
                 $email->save();
             }
         }
     
         return 0;
     }
     
}

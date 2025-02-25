<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;

class PaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;
    public $bill;
    public $user;

    public function __construct($bill)
    {
        $this->bill = $bill;
       
    }

    public function build()
    {
        $pdf = Pdf::loadView('pdf.payment_receipt', ['bill' => $this->bill]);
        $pdfPath = storage_path('app/public/receipt.pdf');
        $pdf->save($pdfPath);

        $email = $this->from('webmaster@gym-concordia.com')
                  ->subject('Payment Receipt')
                  ->view('emails.payment_receipt')
                  ->attach($pdfPath, [
                      'mime' => 'application/pdf',
                  ]);
        return $email;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Payment Receipt',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}

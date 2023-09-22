<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class Prendre_Contact_Controller extends Controller
{
    public function traitement_prendre_contact(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
            'name' => 'required|string',
            'send_me' => 'required|in:1,2,3'
        ]);
    
        $recaptchaToken = $request->input('g-recaptcha-response');
    
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => '6Lfcl0UoAAAAAIZ2O_Owsmx0DjGj3Rad98DJrE_3',
                'response' => $recaptchaToken
            ]
        ]);
    
        $recaptchaResponse = json_decode($response->getBody(), true);
    
        if (!$recaptchaResponse['success']) {
            return redirect()->back()->withErrors(['captcha' => 'reCAPTCHA verification failed.'])->withInput();
        }
    
        $this->sendEmail($validatedData);
    
        return redirect()->back()->with('success', 'votre message a été envoyé avec succès!')->with('sent', true);
    }
    
  
  private function sendEmail($data)
  {
      $recipients = [
          1 => 'webmaster@gym-concordia.com',
          2 => 'tresorier@gym-concordia.com',
          3 => 'president@gym-concordia.com',
      ];
  
      $receiverEmail = $recipients[$data['send_me']];
      Mail::to($receiverEmail)->send(new ContactFormMail($data));
  }

}

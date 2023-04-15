<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class ForgotUsernameController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.username_reminder');
    }

    public function sendUsernameEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);


        $email = $request->input('email');
        $users = User::where('email', $email)->get();
        if ($users->isEmpty()) {
            return back()->withErrors(['email' => 'Aucun utilisateur trouvé avec cet e-mail.']);
        }

        Mail::send('emails.username_reminder', ['users' => $users, 'email' => $email], function (Message $message) use ($email) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($email);
            $message->subject('Rappel d\'identifiant');
        });
        

        return back()->with('status', 'Votre identifiant a été envoyé à votre adresse e-mail.');
    }
}

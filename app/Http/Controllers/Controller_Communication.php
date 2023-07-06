<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;

use App\Models\MailHistory;
use App\Models\LiaisonShopArticlesBill;
use App\Models\User;
use App\Models\Shop_category;
use App\Models\Shop_service;
use App\Mail\UserEmail;
use App\Models\shop_article_1;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use App\Mail\CommunicationEmail;
use App\Models\EmailQueue; 

require_once(app_path().'/fonction.php');


class Controller_Communication extends Controller
{
   function index(Request $request)
   {


        $saison_list = Shop_article::select('saison')->distinct('name')->orderBy('saison', 'desc')->get();

        
        $user = Auth::user();
        if ($user->role >= 90) {
            $shop_articles = Shop_article::
                             orderBy('saison', 'desc')
                             ->orderBy('title', 'asc')
                             ->get();

            } else {
                $shop_articles = Shop_article::leftJoin('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')
                ->where('shop_article.type_article', '=', 1)
                ->whereRaw('JSON_CONTAINS(shop_article_1.teacher, \'[' . $user->user_id . ']\')')
                ->orderBy('saison', 'desc')
                ->orderBy('shop_article.title', 'asc')
                ->get();
            }

        return view('Communication/page_envoi_de_mail', compact('shop_articles','saison_list'));
   
}

public function getEmails(Request $request)
{
    $userIds = $request->input('userIds');
    
    $users = User::whereIn('user_id', $userIds)->get();

    // Mapper les utilisateurs pour récupérer les emails des parents si l'utilisateur est un enfant et qu'il n'a pas d'email
    $emails = $users->map(function ($user) {
        if ($user->family_level === 'child' && empty($user->email)) {
            $parentEmails = User::where('family_id', $user->family_id)
                                ->where('family_level', 'parent')
                                ->pluck('email');
            return $parentEmails;
        } else {
            return $user->email;
        }
    })->flatten()->unique(); 
    return response()->json($emails);
}


public function sendEmails(Request $request)
{
    // For testing purposes, we're only going to use one email
    $emails = $request->input('emails');
    $subject = $request->input('subject');
    $content = $request->input('content');

    $authUser = Auth::user();
    $fromEmail = config('mail.from.address');
    $fromName = config('mail.from.name');
    $senderName = $authUser->lastname . ' ' . $authUser->name;

    if (Str::endsWith($authUser->email, '@gym-concordia.com')) {
        $fromEmail = $authUser->email;
        $fromName = ' Gym Concordia ['.$authUser->lastname . ' ' . $authUser->name.']';
    }

    if ($authUser->username == 'eferandel') {
        $fromEmail = 'president@gym-concordia.com';
        $fromName = 'Gym Concordia [Président]';
        $senderName = 'Elric Ferandel - Président';
    } else if ($authUser->username == 'mferandel') {
        $fromEmail = 'tresorier@gym-concordia.com';
        $fromName = ' Gym Concordia [Trésorier]';
        $senderName = 'Michel Ferandel - Trésorier';
    }

    // Loop over the list of recipient emails
    foreach ($emails as $recipientEmail) {
        // Assuming you have a User model with a 'name' attribute
        $recipient = User::where('email', $recipientEmail)->first();
        $recipientName = $recipient->lastname . ' ' . $recipient->name; 
    
        // Create a new record in the email_queue table for each recipient
        EmailQueue::create([
            'recipient' => $recipientEmail,
            'recipientName' => $recipientName,
            'subject' => $subject,
            'content' => $content,
            'sender' => $fromEmail,
            'fromName' => $fromName,
            'senderName' => $senderName,
            'status' => 'pending',
        ]);
    }
    
    // Return a response or redirect as needed
    return response()->json(['message' => 'Emails envoyés avec succès.']);
}



public function historique (){
    return view('Communication/historique');
}

public function getBuyersForShopArticle($id) {
    $buyersIds = retourner_buyers_dun_shop_article($id);
    $buyers = User::whereIn('user_id', $buyersIds)->select('user_id', 'name', 'lastname')->orderBy('name')->get();
    return response()->json($buyers);
}


function display_by_saison(Request $request){
        
    $saison   = $_POST['saison'];

    return redirect()->route('index_communication', ['saison' => $saison])->with('submitted', true);


}










   function get_info(Request $request, $article_id){

    if($request->ajax()){
    
        return response()->json();
    }

  }


  public function index_u(Request $request)
  {
      $user = User::paginate(10);       
      return view('Communication/Form_email', compact('user'));

  }

/*
  public function sendEmail_u(Request $request)
  {
      $users = User::whereIn("user_id", $request->ids)->get();

      foreach ($users as $key => $user) {
          Mail::to($user->email)->send(new UserEmail($user));
      }

      return response()->json(['success'=>'Send email successfully.']);
  }

*/

  function traitement(Request $request){

    $articles_tab = [] ;
    $final_tab = [] ;
    $articles_tab =  $request->input('article') ;

    foreach ($articles_tab as $d ) {
        $mytab = retourner_buyers_dun_shop_article($d) ;
        $tab = array(
            $d =>  $mytab
        ) ;
       
    }

    return view('Communication/userEmail',compact('final_tab')) ;


  }



  function email_sender(Request $request){

    $successMessage = "votre email a été envoyé avec succès";
    $errorMessage = "l'envoi de votre email a échoué" ;

    $emailSent = 'false';



        $myData = (array)$request->input('tab_selected_users');
        $titre = $request->input('inputValue');
        $text_area = $request->input('text_area');
        $email_sender = $request->input('email_sender') ;
        $name_sender  = $request->input('userName') ;
        $taille_data = count($myData);

        
        if ($taille_data > 0) {

            for ($i=0; $i <$taille_data ; $i++) { 
                $user = User::findOrFail($myData[$i]); // Find the user by ID or throw an exception
                $email = $user->email; 
                
                // Get the user's email address
                //sendEmailToUser($myData[$i],$titre,$text_area,$email_sender,$name_sender) ;

                envoiEmail2($email_sender, $text_area,$email,$name_sender, $titre) ;
                $emailSent = 'true';
            }
        }elseif($taille_data<=0){

           
        }else{
            $user = User::findOrFail($myData[0]); // Find the user by ID or throw an exception
            $email = $user->email;
           // sendEmailToUser($myData[0],$titre,$text_area,$email_sender,$name_sender) ;
            envoiEmail2($email_sender, $text_area,$email,$name_sender, $titre) ;
            $emailSent = 'true';
        }

    
       // return view('Commnication/email_page',compact('myData'))->with('user', auth()->user()) ;
       // return redirect('Communication/email_sender')->with('myData', $myData)->with('user', auth()->user()) ;
        //return 'eye' ;
        if ($emailSent=='true') {
            return response()->json(['status' => 'success', 'message' => $successMessage]);
        } else {
            return response()->json(['status' => 'error', 'message' => $errorMessage]);
        }
    



    }
    

    function email_page(){

        return view('Communication/email_page')->with('user', auth()->user()) ;

    }

    
 


}









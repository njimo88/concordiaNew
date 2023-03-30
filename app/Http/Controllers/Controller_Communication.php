<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;

use App\Models\LiaisonShopArticlesBill;
use App\Models\User;
use App\Models\Shop_category;
use App\Models\Shop_service;
use App\Mail\UserEmail;
use App\Models\shop_article_1;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

require_once('../app/fonction.php');


class Controller_Communication extends Controller
{
   function index(Request $request){


   $saison = $request->input('saison'); // saison selectionne 

   $saison_actu = saison_active() ;

   $saison_list = Shop_article::select('saison')->distinct('name')->get();

   // ----------------------- requete liee a la saison actuelle ---------------------------------------------
    $users = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article','shop_article.title')->distinct()
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
    ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')->where('saison', $saison_actu)->get();


      $shop_article = Shop_article::select('*')->where('saison', $saison_actu)->distinct('id_shop_article')->get();
      $shop_article_lesson =  shop_article_1::select('shop_article_1.teacher', 'shop_article.title','shop_article_1.id_shop_article')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison', $saison_actu)->get();


     $users_lesson = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article','shop_article.title','shop_article_1.teacher')->distinct()
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
    ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')
    ->join('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')

    ->where('shop_article.type_article',1)
    ->where('saison', $saison_actu)->get();

    $uuser =  $users ;

    $uusers_lesson = $users_lesson ;

    //---------------------------------------------------------------------------------------------


     // ----------------------- requete liee a la saison choisie ---------------------------------------------
     $users_pick = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article','shop_article.title')->distinct()
     ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
     ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')->where('saison', $saison)->get();
 
 
       $shop_article_pick = Shop_article::select('*')->where('saison', $saison)->distinct('id_shop_article')->get();

       $shop_article_lesson_pick=  shop_article_1::select('shop_article_1.teacher', 'shop_article.title','shop_article_1.id_shop_article')
         ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison', $saison)->get();
 
 
      $users_lesson_pick = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article','shop_article.title','shop_article_1.teacher')->distinct()
     ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
     ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')
     ->join('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')
 
     ->where('shop_article.type_article',1)
     ->where('saison', $saison)->get();
 
    
 
     //---------------------------------------------------------------------------------------------
 
        

    //   return view('Communication/new_email',compact('shop_article','uuser','data'))->with('user', auth()->user()) ;
   // return sendEmailToUser(140,'MONSIEUR FERANDEL',[10,22,33]);
     //   dd($shop_article_lesson);
   return view('Communication/page_envoi_de_mail',compact('shop_article','uuser','shop_article_lesson','uusers_lesson','saison_list','saison_actu','users_pick','shop_article_pick','shop_article_lesson_pick','users_lesson_pick','saison'))->with('user', auth()->user()) ;
   
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



                envoiEmail($email_sender, $text_area,$email,$name_sender) ;
                $emailSent = 'true';
            }
        }elseif($taille_data<=0){

           
        }else{
            $user = User::findOrFail($myData[0]); // Find the user by ID or throw an exception
            $email = $user->email;
           // sendEmailToUser($myData[0],$titre,$text_area,$email_sender,$name_sender) ;
            envoiEmail($email_sender, $text_area,$email,$name_sender) ;
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









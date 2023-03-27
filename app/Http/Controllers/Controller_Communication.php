<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;

use App\Models\LiaisonShopArticlesBill;
use App\Models\User;
use App\Models\Shop_category;
use App\Models\Shop_service;
use App\Mail\UserEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

require_once('../app/fonction.php');


class Controller_Communication extends Controller
{
   function index(){

   $saison_actu = saison_active() ;

    $users = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article')->distinct()
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
    ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')->where('saison', $saison_actu)->get();


 
        $shop_article = Shop_article::select('*')->where('saison', $saison_actu)->distinct('id_shop_article')->get();
        $uuser =  $users ;

    //   return view('Communication/new_email',compact('shop_article','uuser','data'))->with('user', auth()->user()) ;
   // return sendEmailToUser(140,'MONSIEUR FERANDEL',[10,22,33]);

    return view('Communication/form_email',compact('shop_article','uuser','data'))->with('user', auth()->user()) ;
   
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

        $myData = (array)$request->input('tab_selected_users');
        $titre = $request->input('inputValue');
        $text_area = $request->input('text_area');
        $taille_data = count($myData);

        if ($taille_data > 0) {

            for ($i=0; $i <$taille_data ; $i++) { 
                sendEmailToUser($myData[$i],$titre,'text_area') ;
            }
        }else{

            sendEmailToUser($myData[0],$titre,$text_area) ;
        }

    
       // return view('Commnication/email_page',compact('myData'))->with('user', auth()->user()) ;
       // return redirect('Communication/email_sender')->with('myData', $myData)->with('user', auth()->user()) ;
        return 'eye' ;
    }
    

    function email_page(){

        return view('Communication/email_page')->with('user', auth()->user()) ;

    }
 


}









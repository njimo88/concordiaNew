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


    /*
    $users = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article')
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
    ->get();
    */
    $users = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article')
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
    ->paginate(10);


        $shop_article = Shop_article::paginate(50);
        $uuser =  $users ;
      //  return view('Communication/form_email',compact('shop_article','uuser'))->with('user', auth()->user()) ;
     return destinataires_du_mail(312);

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


  public function sendEmail_u(Request $request)
  {
      $users = User::whereIn("user_id", $request->ids)->get();

      foreach ($users as $key => $user) {
          Mail::to($user->email)->send(new UserEmail($user));
      }

      return response()->json(['success'=>'Send email successfully.']);
  }



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







}









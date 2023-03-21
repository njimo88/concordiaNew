<?php

namespace App\Http\Controllers;
use App\Models\Shop_article;

use App\Models\LiaisonShopArticlesBill;
use App\Models\User;
use App\Models\Shop_category;

use Illuminate\Http\Request;
require_once('../app/fonction.php');

class Controller_club extends Controller
{
    //
    function index_cours(Request $request){

        $saison_actu = saison_active() ;

        $saison = $request->input('saison');

        $shop_article = Shop_article::where('saison',$saison)->where('type_article',1)->get() ;

        $shop_article_first= Shop_article::where('saison', $saison_actu)->where('type_article',1)->get() ;

         $saison_list = Shop_article::select('saison')->distinct('name')->get();

        
         return view('club/cours_index',compact('saison_list','saison','shop_article','shop_article_first'))->with('user', auth()->user()) ;
    }

    function index_include(Request $request){
        
        $saison = $_POST['saison'];
        $s_saison = $request->input('saison');


        $users = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article')
        ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
        ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')->where('saison', $saison)->get();

    

        return redirect()->route('index_cours', ['saison' => $saison])->with('submitted', true);

      //  return view('club/include-page',compact('users','saison'))->with('user', auth()->user());

    
    }

   

    public function display_form_cours($id)
{
    // Retrieve the data for the specified ID from the database
    $shop_article = Shop_Article::find($id);
    $buyers = Donne_User_article_Paye($id);

    $user = User::get() ;
    $requete_articles = Shop_article::get() ;

   //dd($user) ;
  //  return view('/shop_article_cours_ajax', compact('user','buyers','requete_articles'))->with('user', auth()->user());


}

public function form_appel_method($id){
    $shop_article = Shop_Article::find($id);
    $buyers = Donne_User_article_Paye($id);
  
    $user = User::paginate(10);  
    $users = User::select("*")->whereIn('user_id', $buyers)->get();

    $the_id = $id ;

   // dd($users);            
      
    return view('/formulaire_appel', compact('users','buyers','the_id'))->with('user', auth()->user());
    


}

public function enregister_appel_method($id , Request $request){

return $id;




}





 
}

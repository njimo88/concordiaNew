<?php

namespace App\Http\Controllers;
use App\Models\Shop_article;
use App\Models\shop_article_1;

use App\Models\LiaisonShopArticlesBill;
use App\Models\User;
use App\Models\Shop_category;
use App\Models\appels;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\Preset;
use Illuminate\Support\Facades\DB;

require_once(app_path().'/fonction.php');

class Controller_club extends Controller
{
    //
    function index_cours(Request $request){

        
        /*--------------------------faire l'appel------------------------------ */
        ini_set('memory_limit', '512M');


        $saison_actu = saison_active() ;

        $saison = $request->input('saison');

        /*------------------------------------- requetes pour les teachers ------------------------------*/

       
       
            // requete pour la saison active
        $shop_article_lesson =  shop_article_1::select('shop_article_1.teacher', 'shop_article.title','shop_article_1.id_shop_article','shop_article.stock_ini','shop_article.stock_actuel')
          ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison', $saison_actu)->get();
         
          $users_saison_active = User::select('users.user_id','users.name','users.lastname','users.phone','users.birthdate','users.email','liaison_shop_articles_bills.id_shop_article')
            ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
            ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
            ->where('shop_article.saison', $saison_actu)
            ->whereIn('shop_article.type_article', [0, 1])
            ->where('bills.status', '>', 9)
            ->distinct('users.user_id')
            ->orderBy('users.name', 'ASC')
            ->get();



            //requete pour la saison choisie
          $shop_article_lesson_choisie =  shop_article_1::select('shop_article_1.teacher', 'shop_article.title','shop_article_1.id_shop_article','shop_article.stock_ini','shop_article.stock_actuel')
          ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison',  $saison)->get();
          $users_saison_choisie = User::select('users.user_id', 'users.name', 'users.lastname','users.email','users.phone','users.birthdate','liaison_shop_articles_bills.id_shop_article')
          ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
          ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')->where('saison', $saison)
          ->where('type_article',1)->get();
        /* ------------------------------------------requetes pour l'admin------------------------------*/

        $shop_article = Shop_article::where('saison',$saison)->where('type_article',1)->get() ;
        $shop_article_teacher = Shop_article::select('*')->where('saison', $saison_actu)->distinct('id_shop_article')->get();
     
        $shop_article_first = Shop_article::where('saison', $saison_actu)->whereIn('type_article', [0, 1])
        ->orderBy('title', 'ASC')
        ->get();
         $saison_list = Shop_article::select('saison')->distinct('name')->orderBy('saison', 'ASC')->get();
       

       // dd( $users_saison_active_test);

     return view('club/cours_index',compact('saison_list','saison','shop_article','shop_article_first','shop_article_lesson','shop_article_lesson_choisie','users_saison_choisie','users_saison_active'))->with('user', auth()->user()) ;
           

    }


    public function get_data_table(Request $request, $article_id){


        return $article_id ;


        
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
  
  
    $users = User::select("*")->whereIn('user_id', $buyers)->get();

    $the_id = $id ;

   // dd($users);            
      
    return view('/formulaire_appel', compact('users','buyers','the_id'))->with('user', auth()->user());
    


}

public function display_info_user($id){

    $the_id = $id ;
    $info_user = User::where('user_id',$id)->get() ;
    
    $requete_user_family_id = User::where('user_id',$id)->value('family_id');
    $tab =  User::where('family_id', $requete_user_family_id) ->where('family_level','parent')->get();




    return view('club/modal_info', compact('info_user','tab','the_id'))->with('user', auth()->user());


}


public function modif_user($id,Request $request){

    $user = User::find($id);

    $validatedData = $request->validate( [
        'username' => 'nullable|string|max:255',
        'name' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
        'lastname' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
        'email' => [ 'nullable','string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
        'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
        'profession' => 'string|max:191',
        'birthdate' => 'required|date|before:today',
        'address' => 'required',
        'zip' => 'required|numeric',
        'city' => 'required',
        'nationality' => 'required',
        'licenceFFGYM' => ['nullable','regex:/^\d{5}\.\d{3}\.\d{5}$/'],

    ], $messages = [
        'username.required' => "Le champ nom d'utilisateur est requis.",
        'username.max' => "Le nom d'utilisateur ne doit pas dépasser 255 caractères.",
        'name.required' => "Le champ nom est requis.",
        'name.alpha' => "Le nom doit être une chaîne de caractères.",
        'lastname.required' => "Le champ prénom est requis.",
        'lastname.alpha' => "Le prénom doit être une chaîne de caractères.",
        'email.required' => 'Le champ :attribute est requis.',
        'email' => "Le format de l'adresse e-mail est invalide.",
        'email.unique' => "L'adresse e-mail est déjà utilisée.",
        'password.required' => "Le champ mot de passe est requis.",
        'password.min' => "Le mot de passe doit contenir au moins 8 caractères.",
        'password.confirmed' => "La confirmation du mot de passe ne correspond pas.",
        'phone.required' => "Le champ numéro de téléphone  est requis.",
        'phone.regex' => "Le format du numéro de téléphone est invalide.",
        'gender.required' => "Le champ sexe est requis.",
        'birthdate.required' => 'La date de naissance est requise',
        'birthdate.date' => 'Format de date non valide',
        'birthdate.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
        'profession.alpha' => "La profession doit être une chaîne de caractères.",
        'address.required' => "Le champ address est requis.",
        'zip.required' => "Le champ code postal est requis.",
        'zip.regex' => "Le code postal doit être au format 12345 ou 12345-1234.",
        'city.required' => "Le champ ville est requis.",
        'city.alpha' => "La ville doit être une chaîne de caractères.",
        'country.required' => "Le champ pays est requis.",
        'licenceFFGYM.required' => "Le champ licence FFGYM est requis.",
        'licenceFFGYM.regex' => "Le format de la licence FFGYM est invalide.",
    ]);

  
        $user->update($request->all());

        return redirect()->route('index_cours')->with('success', ' mise à jour effectuée avec succès');
}

/*

public function enregister_appel_method_test($id , Request $request){

            $appels           = new appels ;

            $appels->id_cours = $id ;
            $appels->date     = $request->input('date_appel');

            $thedate = $appels->date ;  

    
           
            $tab_user = (array)$request->input('user_id');
            $length_tab_user = count($tab_user);

            $tab_presence =  (array)$request->input('marque_presence');
            $length_tab_presence = count($tab_presence);



            dd($tab_user);

            $ladifference = $length_tab_user - $length_tab_presence  ; // je recupere la difference de taille entre le tableau des users et le nombre d'element checked
           

            if ( $length_tab_user == $length_tab_presence) {

                        $pairedArray = array_combine($tab_user,$tab_presence );
                     $my_json  = json_encode($pairedArray,JSON_NUMERIC_CHECK);
            }else{

                $myArray = array_fill(0, $ladifference, 0);
                $mergedArray = array_merge($tab_presence,$myArray);

                $pairedArray = array_combine($tab_user, $mergedArray);
              
                 $my_json  = json_encode($pairedArray,JSON_NUMERIC_CHECK);

            }

            $appels->presents = $my_json ;
       
          //  $appels->save() ;


           // return redirect()->route('index_cours')->with('success', " l'appel a été effectué avec succès");
        

}

*/


public function enregister_appel_method($id , Request $request){

   
    $tab_presence2 = [] ;
    $tab_presence_final = [] ;
    $tab_presence3 = [] ;
    $appels           = new appels ;

    $appels->id_cours = $id ;
     
    $thedate  = $request->input('date_appel');
    $appels->date = $thedate ;

    $tab_user = (array)$request->input('user_id');
    $tab_presence =  (array)$request->input('marque_presence'); // informations venant des checkboxes
   
    
    // Use array_keys() to extract only the keys of the array (on recupere les indices qui sont en fait les ID des users)
        $keys = array_keys($tab_presence);

    
                foreach($tab_user as $user){

                            if (in_array($user,$keys)){          

                               // $tab_presence2[$user]  = 1 ;
                                $tab_presence2 = array(
                                    $user => 1 
                                );
                        
                            }else{

                              //  $tab_presence2[$user]  = 0 ;
                                $tab_presence2 = array(
                                    $user => 0 
                                );
                                  
                                }

                                $tab_presence3[] =  $tab_presence2  ;
                                $tab_presence2 = []  ;
                   
                   
                }
                           
                $my_json  = json_encode($tab_presence3,JSON_NUMERIC_CHECK);

                $new_data = [];

                foreach ($tab_presence3 as $d) {
                    foreach ($d as $key => $value) {
                        $new_data[$key] = $value;
                    }
                }

                // Output the new data as a JSON string
                $my_json = json_encode($new_data);
              
                $appel_verif = appels::where('id_cours', $id)->where('date',$thedate)->get();
               // dd($appel_verif);
               
                if ($appel_verif->count() > 0) {
                   appels::where('id_cours', $id)->where('date', $thedate)->update(['presents' => $my_json]);
                   return redirect()->route('index_cours')->with('success', " l'appel a été modifié  avec succès");

                  

                }else{

                   $appels->presents = $my_json ;
     
                   $appels->save() ;
                   return redirect()->route('index_cours')->with('success', " l'appel a été effectué avec succès");

                  
                }
               
               

                /*$appel_verif = appels::where('id_cours', $id)->where('date',$thedate)->get();

                if ($appel_verif){

                    appels::where('id_cours', $id)
                    ->where('date', $thedate)
                    ->update(['presents' =>  $my_json]);

                    return  $appel_verif ;
                   

                }else{

                   // 
                    return 0 ;
                   
                   // $appels->save() ;
                }
                */
                                                            





             

  return redirect()->route('index_cours')->with('success', " l'appel a été effectué avec succès");


}



function display_historique_method($id){

    $id_cours = $id ;
    $tab = [] ;
    $tab2 = [] ;
    $present = [] ;
    $appel = appels::where('id_cours',$id)->get();

    foreach($appel as $data){

        foreach ((array)json_decode($data->presents) as $key => $value) {
            $tab[] = $key ;
            $tab2[] = $value ;
           
          
       }
       $present [] = array( $data->date => $tab2) ;
        $tab2 = [] ;


    }

    $users = User::select("*")->whereIn('user_id', $tab)->get();
    
    /*
       foreach ($present as $value) {
        foreach($value as $key => $val)
        dd($key) ;

       } 

       */
      //$present = json_encode($present,JSON_NUMERIC_CHECK) ;

      
    return view('club/historique_view',compact('id_cours','appel','users','present'))->with('user', auth()->user());
}
}

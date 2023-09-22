<?php

namespace App\Http\Controllers;

use App\Models\liasion_shop_categorie;
use App\Models\Shop_article;
use App\Models\Shop_category;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\rooms;
use App\Models\shop_article_0;
use App\Models\shop_article_1;
use App\Models\shop_article_2;
use App\Models\Parametre;
use Illuminate\Http\Request;
require_once(app_path().'/fonction.php');
class Article_Controller extends Controller

{
    // Page d'affichages de la data table d'articles
    public function index(Request $request){
        $S_active = saison_active();
        $saison = $request->input('saison');
       
         $saison_list = Shop_article::select('saison')->distinct('name')->get();
         $requete_article = Shop_article::where('saison',$S_active)->get() ;
         $requete_article_pick = Shop_article::where('saison',  $saison)->get() ;

        
        return view('Articles/MainPage_article',compact('requete_article','saison_list','saison','requete_article_pick'))->with('user', auth()->user()) ;
    }

    function index_include(Request $request){
        
        $saison = $_POST['saison'];
        $s_saison = $request->input('saison');

        return redirect()->route('index_article', ['saison' => $saison])->with('submitted', true);

    
    }

    public function TESTSAISON(Request $request){
        $la_saison =  $request->saison ;
        $requete_article= Shop_article::select('*')->where('saison',$request->saison)->paginate(50);
        
        //=  $requete_article = Shop_article::paginate(50) ;
        $saison_list = Shop_article::select('saison')->distinct('name')->get();
        $html = " ";
       // $data = view('TESTSAISON',compact('requete_article'))->render();
       echo view('TESTSAISON')->with(['requete_article' => $requete_article, 'saison_list' => $saison_list, 'la_saison' => $la_saison])->with('user', auth()->user());
       // return view('TESTSAISON', compact('requete_article','saison_list'))->with('user', auth()->user()) ;
       // return response()->json(['html' => $data]);
    

    }










    /* Fonction permet de generer dans le formulaire interactive creer une seance 
       la liste deroule des differentes salles 
    */

    public function test_create(){

        $requete_room = rooms::get();
        $var = '';

        foreach ($requete_room as $room) :
            $var .= '<option value=' . $room->id_room . '>' . $room->name . '</option>';
        endforeach;

        return '<select name="room[]">' . $var . '</select>';
    }
    

    // Affichage du formulaire creation des produits de type membre
    public function index_create_member(){

        
        $requete_article = Shop_article::get() ;
        $requete_cate = Shop_category::get() ;

        $requete_prof = User::select("*")->where('role','>', 29)->get();

        $saison_list = Shop_article::select('saison')->distinct('name')->get();

        return view('Articles/Create_article_member',compact('requete_article','requete_cate','saison_list','requete_prof'))->with('user', auth()->user()) ;
    }
// Affichage du formulaire creation des produits de type produit
    public function index_create_produit(){

        
        $requete_article = Shop_article::get() ;
        $requete_cate = Shop_category::get() ;

        $requete_prof = User::select("*")->where('role','>', 29)->get();

        $saison_list = Shop_article::select('saison')->distinct('name')->get();

        return view('Articles/Create_article_produit',compact('requete_article','requete_cate','saison_list','requete_prof'))->with('user', auth()->user()) ;
    }
// Affichage du formulaire creation des produits de type lesson
    public function index_create_lesson(){

        
        $requete_article = Shop_article::get() ;
        $requete_cate = Shop_category::get() ;

        $requete_prof = User::select("*")->where('role','>', 29)->get();

        $saison_list = Shop_article::select('saison')->distinct('name')->get();

        return view('Articles/Create_article_lesson',compact('requete_article','requete_cate','saison_list','requete_prof'))->with('user', auth()->user()) ;
    }

//------------------- CREATION DES DIFFERENTS TYPES D'ARTICLES-------------------------



    public function inserer_article_member(Request $request){

        $validatedData = $request->validate( [
            'saison' => 'required|numeric',
            'title'  =>  ['required', 'alpha', 'max:255'],
            'image'  =>  ['required', 'alpha', 'max:255'],
             'ref'  =>  ['required', 'alpha', 'max:255'],
            'startvalidity' => 'required|date',
            'endvalidity'   => 'required|date',
            'agemin' => 'required|numeric|gt:0',
            'agemax' => 'required|numeric|gt:0',
            'price' => 'required|numeric',
            'price_indicative' => 'required|numeric',
            'totalprice' => 'required|numeric',
            'stock_ini'  =>    'required|numeric',
            'stock_actuel' => 'required|numeric',
            'alert_stock'  => 'required|numeric',

            'max_per_user'      =>  'required|numeric|gt:0',
            'short_description'  => ['required', 'alpha', 'max:255'],
            'editor1'       =>  ['required', 'alpha', 'max:255'],

            'prix_adhesion'  =>    'required|numeric|gt:0',
            'prix_assurance'  =>   'required|numeric|gt:0',
            'prix_licence_fede' =>  'required|numeric|gt:0',

         
           
    
        ], $messages = [
            'title.required' => "Le champ titre est requis.",
            'title.max' => "Le titre ne doit pas dépasser 255 caractères.",
            'image.required' => "Le champ image est requis.",
            'image.alpha' => "l'image doit être une chaîne de caractères.",
            'ref.required' => "Le champ ref est requis.",
            'ref.alpha' => "ref doit être une chaîne de caractères.",
            'agemin.required' => 'age minimum est requis.',
            'agemax.required' => 'age maximum est requis.',

        ]);
    
    
   
        $article  = new Shop_article;
 
        $article->saison = $request->input('saison');
        $article->title  = $request->input('title');
        $article->image  = $request->input('image');
        $article->ref    = $request->input('ref');
        
       
        $article->startvalidity = $request->input('startvalidity');
        $article->endvalidity = $request->input('endvalidity');
        $article->need_member = $request->input('need_member'); 
        
        $article->agemin           = $request->input('agemin');
        $article->agemax           = $request->input('agemax');
        $article->price            = $request->input('price');
        $article->price_indicative = $request->input('price_indicative');
        $article->totalprice       = $request->input('price');
        $article->stock_ini         = $request->input('stock_ini');
        $article->stock_actuel      = $request->input('stock_actuel');
        $article->alert_stock       = $request->input('alert_stock');
        $article->type_article      = $request->input('type_article');
        $article->max_per_user      = $request->input ('max_per_user');
        $article->short_description = $request->input('short_description');
        $article->description       = $request->input('editor1') ;

        if($request->has('nouveaute')){
            $article->nouveaute = $request->input('nouveaute');
        }else{
            $article->nouveaute = 0 ;
        }

        if($request->has('afiscale')){
            $article->afiscale = $request->input('afiscale');
        }else{
            $article->afiscale = 0 ;
        }
        
        if($request->has('sex_limit')){
            $article->sex_limit = $request->input('sex_limit');
        }else{
            $article->sex_limit = 0 ;
        }
        if($request->has('sex_limit')){
            $article->selected_limit = $request->input('strict' );
        }else{
            $article->selected_limit = 0 ;
        }
    
        $article->categories =  json_encode($request->input('category'),JSON_NUMERIC_CHECK);
       
        $article->save();
        
      
        // recupere l'id de l'article qu'on a juste cree
        $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();


    //----------------------------------------- inserer les infos dans la table member  --------------------------- //
    
       // on appelle le modele shop_article_0 = member
       $requete_member = new shop_article_0 ;
       $requete_member->id_shop_article  =  $requete_article["id_shop_article"];

       $requete_member->prix_adhesion         =  $request->input('prix_adhesion'); 
       $requete_member->prix_assurance        =  $request->input('prix_assurance'); 
       $requete_member->prix_licence_fede     =  $request->input('prix_licence_fede'); 
            
       $requete_member->save();

   
        return redirect()->route('create_article_member')->with('user', auth()->user())->with('success', 'article a été crée avec succès');
       
   

    }

    public function inserer_article_lesson(Request $request){

        $validatedData = $request->validate( [
            'saison' => 'required|numeric',
            'title'  =>  ['required', 'alpha', 'max:255'],
            'image'  =>  ['required', 'alpha', 'max:255'],
             'ref'  =>  ['required', 'alpha', 'max:255'],
            'startvalidity' => 'required|date',
            'endvalidity'   => 'required|date',
            'agemin' => 'required|numeric|gt:0',
            'agemax' => 'required|numeric|gt:0',
            'price' => 'required|numeric',
            'price_indicative' => 'required|numeric',
            'totalprice' => 'required|numeric',
            'stock_ini'  =>    'required|numeric',
            'stock_actuel' => 'required|numeric',
            'alert_stock'  => 'required|numeric',

            'max_per_user'      =>  'required|numeric|gt:0',
            'short_description'  => ['required', 'alpha', 'max:255'],
            'editor1'       =>  ['required', 'alpha', 'max:255'],

           
         
           
    
        ], $messages = [
            'title.required' => "Le champ titre est requis.",
            'title.max' => "Le titre ne doit pas dépasser 255 caractères.",
            'image.required' => "Le champ image est requis.",
            'image.alpha' => "l'image doit être une chaîne de caractères.",
            'ref.required' => "Le champ ref est requis.",
            'ref.alpha' => "ref doit être une chaîne de caractères.",
            'agemin.required' => 'age minimum est requis.',
            'agemax.required' => 'age maximum est requis.',

        ]);
    
















      
        $article  = new Shop_article;
 
        $article->saison = $request->input('saison');
        $article->title  = $request->input('title');
        $article->image  = $request->input('image');
        $article->ref    = $request->input('ref');
        
        
        $article->startvalidity = $request->input('startvalidity');
        $article->endvalidity = $request->input('endvalidity');
        $article->need_member = $request->input('need_member'); 
        
        $article->agemin           = $request->input('agemin');
        $article->agemax           = $request->input('agemax');
        $article->price            = $request->input('price');
        $article->price_indicative = $request->input('price_indicative');
        $article->totalprice        = 10;
        $article->stock_ini         = $request->input('stock_ini');
        $article->stock_actuel      = $request->input('stock_actuel');
      
        $article->alert_stock       = $request->input('alert_stock');
        $article->type_article      = 1;
        $article->max_per_user      = $request->input ('max_per_user');
        $article->short_description = $request->input('short_description');
        $article->description       = $request->input('editor1') ;

       
        if($request->has('nouveaute')){
            $article->nouveaute = $request->input('nouveaute');
        }else{
            $article->nouveaute = 0 ;
        }

        if($request->has('afiscale')){
            $article->afiscale = $request->input('afiscale');
        }else{
            $article->afiscale = 0 ;
        }
        
        if($request->has('strict')){
            $article->sex_limit = $request->input('strict');
        }else{
            $article->sex_limit = 0 ;
        }
        if($request->has('strict')){
            $article->selected_limit = $request->input('strict' );
        }else{
            $article->selected_limit = 0 ;
        }
    
        $article->categories =  json_encode($request->input('category'),JSON_NUMERIC_CHECK);

        $article->save();

      
        // recupere l'id de l'article qu'on a juste cree
        $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();

        $requete_lesson = new shop_article_1;
        $requete_lesson->id_shop_article  =  $requete_article["id_shop_article"];
 
        // recuperation des input du formulaire et encodage en json avec json_encode
        $stock_ini_tab    = json_encode( array("stock_ini" => (array)(int)$request->input('stock_ini')) );
        $stock_actuel_tab = json_encode( array("stock_actuel" => (array)(int)$request->input('stock_actuel')) );
        $teacher_tab      = json_encode( $request->input('prof'),JSON_NUMERIC_CHECK );

        $lesson_tab       = json_encode( array( 
            
            "room" => $request->input('room'), 
            "start_date" =>  $request->input('startdate'), 
            "end_date" =>   $request->input('enddate')
        
        ),JSON_NUMERIC_CHECK);

        //insertion des donnees dans les champs de la BD
        $requete_lesson->stock_ini    =    $stock_ini_tab;
        $requete_lesson->stock_actuel =    $stock_actuel_tab ;
        $requete_lesson->teacher      =    $teacher_tab ;
        $requete_lesson->lesson       =    $lesson_tab ; 

        $requete_lesson->save();

        return redirect()->route('create_article_lesson')->with('user', auth()->user())->with('success', 'article a été crée avec succès');
            
         
        }    


    public function inserer_article_produit(Request $request){

        $validatedData = $request->validate( [
            'saison' => 'required|numeric',
            'title'  =>  ['required', 'alpha', 'max:255'],
            'image'  =>  ['required', 'alpha', 'max:255'],
             'ref'  =>  ['required', 'alpha', 'max:255'],
            'startvalidity' => 'required|date',
            'endvalidity'   => 'required|date',
            'agemin' => 'required|numeric|gt:0',
            'agemax' => 'required|numeric|gt:0',
            'price' => 'required|numeric',
            'price_indicative' => 'required|numeric',
            'totalprice' => 'required|numeric',
            'stock_ini'  =>    'required|numeric',
            'stock_actuel' => 'required|numeric',
            'alert_stock'  => 'required|numeric',

            'max_per_user'      =>  'required|numeric|gt:0',
            'short_description'  => ['required', 'alpha', 'max:255'],
            'editor1'       =>  ['required', 'alpha', 'max:255'],

           
         
           
    
        ], $messages = [
            'title.required' => "Le champ titre est requis.",
            'title.max' => "Le titre ne doit pas dépasser 255 caractères.",
            'image.required' => "Le champ image est requis.",
            'image.alpha' => "l'image doit être une chaîne de caractères.",
            'ref.required' => "Le champ ref est requis.",
            'ref.alpha' => "ref doit être une chaîne de caractères.",
            'agemin.required' => 'age minimum est requis.',
            'agemax.required' => 'age maximum est requis.',

        ]);
    







      
        $article  = new Shop_article;
 
        $article->saison = $request->input('saison');
        $article->title  = $request->input('title');
        $article->image  = $request->input('image');
        $article->ref    = $request->input('ref');
        
        
        $article->startvalidity = $request->input('startvalidity');
        $article->endvalidity = $request->input('endvalidity');
        $article->need_member = $request->input('need_member'); 
        
        $article->agemin           = $request->input('agemin');
        $article->agemax           = $request->input('agemax');
        $article->price            = $request->input('price');
        $article->price_indicative = $request->input('price_indicative');
        $article->totalprice       = 10;
        $article->stock_ini         = $request->input('stock_ini');
        $article->stock_actuel      = $request->input('stock_actuel');
      
        $article->alert_stock       = $request->input('alert_stock');
        $article->type_article      = $request->input('type_article');
        $article->max_per_user      = $request->input ('max_per_user');
        $article->short_description = $request->input('short_description');
        $article->description       = $request->input('editor1') ;

       
        if($request->has('nouveaute')){
            $article->nouveaute = $request->input('nouveaute');
        }else{
            $article->nouveaute = 0 ;
        }

        if($request->has('afiscale')){
            $article->afiscale = $request->input('afiscale');
        }else{
            $article->afiscale = 0 ;
        }
        
        if($request->has('strict')){
            $article->sex_limit = $request->input('strict');
        }else{
            $article->sex_limit = 0 ;
        }
        if($request->has('strict')){
            $article->selected_limit = $request->input('strict' );
        }else{
            $article->selected_limit = 0 ;
        }
    
        $article->categories =  json_encode($request->input('category'),JSON_NUMERIC_CHECK);

       $article->save();

      
        // recupere l'id de l'article qu'on a juste cree
        $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();

        $requete_produit = new shop_article_2;
        $requete_produit->id_shop_article  =  $requete_article["id_shop_article"];
 
        $requete_produit->declinaison = $request->input('Json_declinaison');
       

        $requete_produit->save();
        return redirect()->route('create_article_produit')->with('user', auth()->user())->with('success', 'article a été crée avec succès');
            

      
         
        }    
    
// methode du get qui affiche le formualaire pre rempli en fonction de l'id de l'article et de son type
          public function duplicate_index($id){
            $id_article = $id ;
            $Id = $id;
            $requete_prof = User::select("*")->where('role','>', 29)->get();
            $rooms = rooms::get();
            $requete_cate = Shop_category::get() ;
            $saison_list = Shop_article::select('saison')->distinct('name')->get();
            $requete_prof = User::select("*")->where('role','>', 29)->get();


            $Shop_article  = Shop_article::where('id_shop_article', $id)->get();

            $shop_article_0 = shop_article_0::where('id_shop_article', $id)->get();
            $Shop_article   = Shop_article::where('id_shop_article', $id)->get();
            $shop_article_1 = shop_article_1::where('id_shop_article', $id)->get();
            $shop_article_2 = shop_article_2::where('id_shop_article', $id)->get();

           if ($shop_article_0 ->count() == 1 ){
                return view('Articles/article_0',compact('Shop_article','saison_list','shop_article_0','requete_cate','requete_prof','id_article'))->with('user', auth()->user()) ;
           }elseif($shop_article_1->count() == 1 ){
                return view('Articles/article_1',compact('Shop_article','saison_list','shop_article_1','requete_cate','requete_prof','id_article','rooms'))->with('user', auth()->user()) ;
           }
           elseif($shop_article_2->count() == 1 ){
            
            return view('Articles/article_2',compact('Shop_article', 'shop_article_1','shop_article_0','shop_article_2','requete_cate','saison_list','requete_prof','id_article','Id','id_article'))->with('user', auth()->user()) ;
        }
           }

 // methode du POST pour effectuer la duplication          

           public function duplicate($id,Request $request){

            $requete_cate = Shop_category::get() ;
            $saison_list = Shop_article::select('saison')->distinct('name')->get();
            $requete_prof = User::select("*")->where('role','>', 29)->get();
           
            $rooms = rooms::get();
    

            // recupere l'id de l'article qu'on a juste cree
            $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();

            $Shop_article  = Shop_article::where('id_shop_article', $id)->get();

            $shop_article_0 = shop_article_0::where('id_shop_article', $id)->get();
           
            $shop_article_1 = shop_article_1::where('id_shop_article', $id)->get();
            $shop_article_2 = shop_article_2::where('id_shop_article', $id)->get();

            // insertion des elements dans la BD table shop_article
            $article  = new Shop_article;
            $article->id_shop_article =  $requete_article["id_shop_article"] + 1 ;
            $article->saison = $request->input('saison');
            $article->title  = $request->input('title');
            $article->image  = $request->input('image');
            $article->ref    = $request->input('ref');
            
            $article->nouveaute = $request->input('nouveaute');
            $article->startvalidity = $request->input('startvalidity');
            $article->endvalidity = $request->input('endvalidity');
            $article->need_member = $request->input('need_member'); 
            
            $article->agemin           = $request->input('agemin');
            $article->agemax           = $request->input('agemax');
            $article->price            = $request->input('price');
            $article->price_indicative = $request->input('price_indicative');
            $article->totalprice       = 10;
            $article->stock_ini         = $request->input('stock_ini');
            $article->stock_actuel      = $request->input('stock_actuel');
            $article->alert_stock       = $request->input('alert_stock');
            $article->type_article      = $request->input('type_article');
            $article->max_per_user      = $request->input ('max_per_user');
            $article->short_description = $request->input('short_description');
            $article->description       = $request->input('editor1') ;
    
            $article->afiscale = $request->input('afiscale');
            $article->sex_limit = $request->input('sex_limit');
            $article->selected_limit = $request->input('strict' );
            $article->categories =  json_encode($request->input('category'),JSON_NUMERIC_CHECK);
    
           $article->save();

           if ($shop_article_0 ->count() == 1 ){
                        //----------------------------------------- inserer les infos dans la table member  --------------------------- //
                            
                            
                            // recupere l'id de l'article qu'on a juste cree
                                $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();
                            
                                // on appelle le modele shop_article_0 = member 
                                $requete_member = new shop_article_0 ;

                            $requete_member->id_shop_article        = $requete_article["id_shop_article"];

                            $requete_member->prix_adhesion         =  $request->input('prix_adhesion'); 
                            $requete_member->prix_assurance        =  $request->input('prix_assurance'); 
                            $requete_member->prix_licence_fede     =  $request->input('prix_licence_fede'); 
                                    
                            $requete_member->save();


                        
                            return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été dupliqué avec succès');
                            
                                        

           }elseif($shop_article_1->count() == 1 ){

                          // recupere l'id de l'article qu'on a juste cree
                          $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();
                          //----------------------------------------- inserer les infos dans la table lesson  --------------------------- //
                          
                    
                            $requete_lesson =  new shop_article_1 ;

                            if(isset($request->room) and isset($request->enddate) and  isset($request->startdate)){
                                $lesson_tab  = json_encode( array( 
                                
                                    "room" => $request->input('room'), 
                                    "start_date" =>  $request->input('startdate'), 
                                    "end_date" =>   $request->input('enddate')
                                
                                ),JSON_NUMERIC_CHECK);

                                $requete_lesson->save();

                            }
                        
    

                            $stock_ini_tab = json_encode( array("stock_ini" => (array)(int)$request->input('stock_ini')) );
                            $stock_actuel_tab = json_encode( array("stock_actuel" => (array)(int)$request->input('stock_actuel')) );
                            $lesson_tab  = json_encode( array( 
                            
                                "room" => $request->input('room'), 
                                "start_date" =>  $request->input('startdate'), 
                                "end_date" =>   $request->input('enddate')
                            
                            ),JSON_NUMERIC_CHECK);
                    
                           


                            $teacher_tab =  json_encode( $request->input('prof'),JSON_NUMERIC_CHECK );
                         
                     



                            $requete_lesson->id_shop_article  =  $requete_article["id_shop_article"];


                            $requete_lesson->stock_ini           =   $stock_ini_tab ;
                            $requete_lesson->stock_actuel        =    $stock_actuel_tab;
                            $requete_lesson->teacher             =    $teacher_tab ;
                            $requete_lesson->lesson              =  $request->input('json') ;

                            $requete_lesson->save();




                        
             return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été dupliqué avec succès');

                           
                              
                
                            
           }
           elseif($shop_article_2->count() == 1 ){

                $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();
            
                    // on appelle le modele shop_article_2 = produit
                $requete_produit = new shop_article_2 ;

                if(isset($request->Json_declinaison)){
                    $requete_produit->declinaison = $request->input('Json_declinaison');
                    $requete_produit->save();
                }
                if(isset($request->editor1)){

                    $article->description = $request->editor1;
                    $article->save();
                }


                $requete_produit->id_shop_article = $requete_article["id_shop_article"]; 

               // $requete_declinaison =  shop_article_2::select('declinaison')->where('id_shop_article',$id)->get();
              
                $requete_produit->declinaison = $request->input('thejson') ;

                $requete_produit->save();
        


            return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été dupliqué avec succès');

          
        
        }




        
                
    }


// SUPPRESSION D'ARTICLES

    public function delete($id)
    {
        
        $Shop_article = Shop_article::where('id_shop_article', $id)->delete();
        $shop_article_1 = shop_article_1::where('id_shop_article', $id)->delete();
        $shop_article_0 = shop_article_0::where('id_shop_article', $id)->delete();
        $shop_article_2 = shop_article_2::where('id_shop_article', $id)->delete();
      
      //  return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');
      return redirect()->back()->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');

    }



//MODIFICATION D'ARTICLES --------------------------------

    public function edit_index($id)
    {
        $Id =$id;
        $requete_cate = Shop_category::get() ;

        $requete_prof = User::select("*")->where('role','>', 29)->get();
        $rooms = rooms::orderBy('name', 'asc')->get();


        $saison_list = Parametre::select('saison')->distinct('name')->get();

        $Shop_article = Shop_article::where('id_shop_article', $id)->get();
        $shop_article_1 = shop_article_1::where('id_shop_article', $id)->get();
        $shop_article_0 = shop_article_0::where('id_shop_article', $id)->get();
        $shop_article_2 = shop_article_2::where('id_shop_article', $id)->get();
        
        $parametre = Parametre::where('activate', 1)->first();

        // Récupérer les articles correspondants
        $articleLicence1 = Shop_article::find($parametre->articles_licence1);
        $articleLicence2 = Shop_article::find($parametre->articles_licence2);
        $articleLicence3 = Shop_article::find($parametre->articles_licence3);
        $articleLicence4 = Shop_article::find($parametre->articles_licence4);
    
        return view('Articles/edit_index',compact('Shop_article','articleLicence1','articleLicence2','articleLicence3','articleLicence4','parametre','shop_article_1','shop_article_0','shop_article_2','requete_cate','saison_list','requete_prof','Id','rooms'))->with('user', auth()->user());

    }

    public function updateLesson(Request $request)
    {
        $shopArticleId = $request->input('shop_article_id');
        $lessonIndex = $request->input('lesson_index');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $room = $request->input('room');
    
        // Récupérer le shop article correspondant
        $shopArticle = shop_article_1::find($shopArticleId);
        if ($shopArticle) {
            $lessons = json_decode($shopArticle->lesson, true);
    
            // Mettre à jour les informations de la séance
            if (isset($lessons['start_date'][$lessonIndex])) {
                $lessons['start_date'][$lessonIndex] = $startDate . ' ' . $startTime;
            }
    
            if (isset($lessons['end_date'][$lessonIndex])) {
                $lessons['end_date'][$lessonIndex] = $endDate . ' ' . $endTime;
            }
    
            if (isset($lessons['room'][$lessonIndex])) {
                $lessons['room'][$lessonIndex] = $room;
            }
    
            // Mettre à jour la colonne "lesson" dans la base de données
            $shopArticle->lesson = json_encode($lessons);
            $shopArticle->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Shop article not found']);
    }
    

       //Modification des articles
    public function edit(Request $request, $id){
            
          // $article= Shop_article::where('id_shop_article', $id)->firstOrFail(); apparemment, la fonction firstorfail force a utiliser id comme primary key
            $article= Shop_article::find($id); 
            $article->update($request->all());
            $id_article = $article->id_shop_article;
            $article_2 = shop_article_2::find($id);    
            $article_1 = shop_article_1::find($id);
            $article_0 = shop_article_0::find($id);
            
           // = Shop_article::select('type_article')->where('id_shop_article',$id)->get();
           $type_article = Shop_article::find($id)->type_article;
        
           if($type_article == 0){
                                if(isset($request->editor1)){

                                    $article->description = $request->editor1;
                                    $article->save();
                                }
                                if($request->has('nouveaute')){
                                    $article->nouveaute = 1;
                                    $article->save();
                                }else{
                                    $article->nouveaute = 0 ;
                                    $article->save();
                                }
                        
                                if($request->has('strict')){
                                    $article->selected_limit = 1;
                                    $article->save();
                                }else{
                                    $article->selected_limit = 0 ;
                                    $article->save();
                                }

                                if($request->has('afiscale')){
                                    $article->afiscale = 1;
                                }else{
                                    $article->afiscale = 0 ;
                                    $article->save();
                                }

                                if(isset($request->category)){
                                 
                                    $article->categories =  json_encode($request->category,JSON_NUMERIC_CHECK);
                                    
                                    $article->save();  
                                    updateArticleCategories($id_article, $request->category);
                                    updateTotalPrice($article);

                                }

                              

                             
                                if($article_0) { 
                                    $article_0->update($request->all()); 
                                }
                                
 
                              
                               

           }elseif($type_article == 1){
                            
            

                                if(isset($request->editor1)){

                                    $article->description = $request->editor1;
                                    $article->save();
                                }
                                if($request->has('nouveaute')){
                                    $article->nouveaute = 1;
                                    $article->save();
                                }else{
                                    $article->nouveaute = 0 ;
                                    $article->save();
                                }
                        
                                if($request->has('strict')){
                                    $article->selected_limit = 1;
                                    $article->save();
                                }else{
                                    $article->selected_limit = 0 ;
                                    $article->save();
                                }

                                if($request->has('afiscale')){
                                    $article->afiscale = 1;
                                }else{
                                    $article->afiscale = 0 ;
                                    $article->save();
                                }

                                if(isset($request->category)){
                                
                                    $article->categories =  json_encode($request->category,JSON_NUMERIC_CHECK);
                                
                                    $article->save(); 
                                    updateArticleCategories($id_article, $request->category); 
                                    updateTotalPrice($article);
                                }


                                if(isset($request->prof)){

                                       $teacher_tab             =    json_encode( $request->input('prof'),JSON_NUMERIC_CHECK );
                                
                                       $article_1->teacher      =    $teacher_tab ;
                                
                                       $article_1->save();  
                                      
                                }

                                if(isset($request->room) and isset($request->enddate) and  isset($request->startdate)){
                                    $lesson_tab  = json_encode( array( 
                                    
                                        "room" => $request->input('room'), 
                                        "start_date" =>  $request->input('startdate'), 
                                        "end_date" =>   $request->input('enddate')
                                    
                                    ),JSON_NUMERIC_CHECK);
                            
                                
                                    $article_1->lesson       = $lesson_tab ; 
                                    $article_1->stock_ini    = json_encode( array("stock_ini" => (array)(int)$request->input('stock_ini')) );
                                    $article_1->stock_actuel = json_encode( array("stock_actuel" => (array)(int)$request->input('stock_actuel')) );
                                    

                                    $article_1->save();
                            
                                   
                             }

                             

                                
                             if($article_1) { 
                                $article_1->update($request->all()); 
                            }
                            
                                if(shop_article_1::hasMultipleTeachers($id_article)) {
                                    $article->image = '/uploads/users/froze/multiple_teachers_image.png';
                                    $article->save();
                                } else {
                                    $article1 =  shop_article_1::find($id_article);
                                    $teachers = json_decode($article1->teacher, true);
                                    $teacherId = $teachers[0]; 
                                    $teacher = User::find($teacherId); 
                                
                                    if($teacher && $teacher->image) {
                                        $article->image = asset($teacher->image);
                                        $article->save(); 
                                    } else {
                                        $article->image = asset('assets/images/default_teacher_image.png'); 
                                        $article->save(); 
                                    }
                                }



             }elseif($type_article ==2){
                            
                                    if(isset($request->editor1)){

                                        $article->description = $request->editor1;
                                        $article->save();
                                    }
                                    if($request->has('nouveaute')){
                                        $article->nouveaute = 1;
                                        $article->save();
                                    }else{
                                        $article->nouveaute = 0 ;
                                        $article->save();
                                    }
                            
                                    if($request->has('strict')){
                                        $article->selected_limit = 1;
                                        $article->save();
                                    }else{
                                        $article->selected_limit = 0 ;
                                        $article->save();
                                    }

                                    if($request->has('afiscale')){
                                        $article->afiscale = 1;
                                    }else{
                                        $article->afiscale = 0 ;
                                        $article->save();
                                    }

                                    if(isset($request->category)){
                                    
                                        $article->categories =  json_encode($request->category,JSON_NUMERIC_CHECK);
                                        $article->save(); 
                                        updateArticleCategories($id_article, $request->category);
                                         
                                        updateTotalPrice($article);
                                    }

                                    if($request->has('sex_limit')){
                                        $article->sex_limit = $request->input('sex_limit');
                                        $article->save();
                                    }else{
                                        $article->selected_limit = 0 ;
                                        $article->save();
                                    }




                                            if($article_2){
                                                $article_2->update($request->all());
                                            }
                            

           }
            
       
        return   redirect()->back()->with('user', auth()->user())->with('success', 'l\'article a été modifié avec succès');

        
    }

// CKEDITOR 




public function upload(Request $request)
 {
     if($request->hasFile('upload')) {
         $originName = $request->file('upload')->getClientOriginalName();
         $fileName = pathinfo($originName, PATHINFO_FILENAME);
         $extension = $request->file('upload')->getClientOriginalExtension();
         $fileName = $fileName.'_'.time().'.'.$extension;
        
         $request->file('upload')->move(public_path('images'), $fileName);
   
         $CKEditorFuncNum = $request->input('CKEditorFuncNum');
         $url = asset('images/'.$fileName); 
         $msg = 'Image uploaded successfully'; 
         $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
         @header('Content-type: text/html; charset=utf-8'); 
         echo $response;
     }
}










// TRAITEMENT DE LA BASE DE DONNEES CREATION DU CHAMP CATEGORIES EN JSON DANS SHOP_ARTICLE 
    public function  JsonProcess(){

        $tab1 = array();
       
        $request1 = Shop_article::get();
        $request2 = liasion_shop_categorie::get();
       
        foreach ($request1 as $data1) {


                        foreach ($request2 as $data2) {
                        
                                if ($data1->id_shop_article == $data2->id_shop_article){
                                                
                
                                            array_push($tab1,$data2->id_shop_category);
                                                
                                            
                                }

                            }
            
             print("=================[.$data1->id_shop_article.]==================<br>");

                        
                        $tab1 = array_unique($tab1);
                        print_r($tab1);
                                                
                                                DB::table('shop_article')
                                                    ->where('id_shop_article',$data1->id_shop_article )
                                                    ->update(['categories' => $tab1]);  
                                                

                        $tab1 = array();
                       
                       
                

}


}







}
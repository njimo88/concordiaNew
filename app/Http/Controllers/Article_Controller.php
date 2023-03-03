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
use Illuminate\Http\Request;

class Article_Controller extends Controller
{
    // Page d'affichages de la data table d'articles
    public function index(){

        $requete_article = Shop_article::paginate(30) ;
        return view('Articles/MainPage_article',compact('requete_article'))->with('user', auth()->user()) ;
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

  /*    
    $validator  = $request->validate([
        'saison' => 'required|numeric',
        'title'  => 'required|max:255',
        'image'  => 'required|max:255|alpha',
        'ref'  => 'required|max:255|alpha',
        'startvalidity' => 'date',
        'endvalidity'   => 'date',
        'agemin' => 'required|numeric|gt:0',
        'agemax' => 'required|numeric|gt:0',
        'price' => 'required|numeric',
        'price_indicative' => 'required|numeric',
        'totalprice' => 'required|numeric',
        'stock_ini' => 'required|numeric',
        'stock_actuel' => 'required|numeric',
        'alert_stock'  => 'required|numeric',

        'type_article' => 'required|numeric',
         'max_per_user'      =>  'required|numeric|gt:0',
          'short_description'  =>'required|alpha',
           'description'       => 'required|alpha',

           'prix_adhesion'  => 'required|numeric|gt:0',
            'prix_assurance'  =>   'required|numeric|gt:0',
            'prix_licence_fede' =>   'required|numeric|gt:0',


       
  ], [
        'agemin' => 'age negative !!!',
        'agemax' => 'age negative !!!',
        'prix_adhesion' => 'age negative !!!',
        'prix_assurance' => 'age negative !!!',
        'prix_licence_fede' => 'age negative !!!',
       


    ]);
    */
     
    
   
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


    public function inserer_article_produit(Request $request){
      
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
                return view('Articles/article_1',compact('Shop_article','saison_list','shop_article_1','requete_cate','requete_prof','id_article'))->with('user', auth()->user()) ;
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
            $article->title  = "new-".$request->input('title');
            $article->image  = $request->input('image');
            $article->ref    = "new-".$request->input('ref');
            
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
          
             // on appelle le modele shop_article_0 = member
             $requete_lesson = new shop_article_1 ;
             $requete_lesson->id_shop_article  =  $requete_article["id_shop_article"];
      
             $input_lesson = [
                "room" => $request->input('room') ,
                "end_date" => $request->input('enddate'),
                "start_date" =>  $request->input('startdate')      
        ];

        $stock_ini = array((int)$request->input('stock_ini')) ;
        $input_stock_ini = ["stock_ini" =>  $stock_ini ];
    
        $stock_actuel = array((int)$request->input('stock_actuel'));
        $input_stock_actuel = ["stock_actuel" =>   $stock_actuel ];
    

             $requete_lesson->teacher           =  json_encode($request->input('prof'),JSON_NUMERIC_CHECK); 
             $requete_lesson->lesson            =  json_encode($input_lesson,JSON_NUMERIC_CHECK); 
             $requete_lesson ->stock_ini        =  json_encode($input_stock_ini); 
             $requete_lesson->stock_actuel      =  json_encode($input_stock_actuel); 
                  
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
      
        return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');

    }



//MODIFICATION D'ARTICLES --------------------------------

    public function edit_index($id)
    {
        $Id =$id;
        $requete_cate = Shop_category::get() ;

        $requete_prof = User::select("*")->where('role','>', 29)->get();

        $saison_list = Shop_article::select('saison')->distinct('name')->get();

        $Shop_article = Shop_article::where('id_shop_article', $id)->get();
        $shop_article_1 = shop_article_1::where('id_shop_article', $id)->get();
        $shop_article_0 = shop_article_0::where('id_shop_article', $id)->get();
        $shop_article_2 = shop_article_2::where('id_shop_article', $id)->get();
        $room = rooms::get();
        
    
        return view('Articles/edit_index',compact('Shop_article','shop_article_1','shop_article_0','shop_article_2','requete_cate','saison_list','requete_prof','Id','room'))->with('user', auth()->user());

    }

       //Modification des articles
    public function edit(Request $request, $id){
            
          // $article= Shop_article::where('id_shop_article', $id)->firstOrFail(); apparemment, la fonction firstorfail force a utiliser id comme primary key
            $article= Shop_article::find($id); 
            $article->update($request->all());
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
                                }

                              

                             
                                $article_0->update($request->all()); 
 
                              
                               

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
            }
           $article_1->update($request->all());
                         



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
                }

                if($request->has('sex_limit')){
                    $article->sex_limit = 1;
                    $article->save();
                }else{
                    $article->selected_limit = 0 ;
                    $article->save();
                }




                        
                $article_2->update($request->all());
                            

           }
            
         return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'l\'article a été modifié avec succès');
        
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
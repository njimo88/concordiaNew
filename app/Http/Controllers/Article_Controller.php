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
        return view('Articles/MainPage_article',compact('requete_article')) ;
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

        return view('Articles/Create_article_member',compact('requete_article','requete_cate','saison_list','requete_prof')) ;
    }
// Affichage du formulaire creation des produits de type produit
    public function index_create_produit(){

        
        $requete_article = Shop_article::get() ;
        $requete_cate = Shop_category::get() ;

        $requete_prof = User::select("*")->where('role','>', 29)->get();

        $saison_list = Shop_article::select('saison')->distinct('name')->get();

        return view('Articles/Create_article_produit',compact('requete_article','requete_cate','saison_list','requete_prof')) ;
    }
// Affichage du formulaire creation des produits de type lesson
    public function index_create_lesson(){

        
        $requete_article = Shop_article::get() ;
        $requete_cate = Shop_category::get() ;

        $requete_prof = User::select("*")->where('role','>', 29)->get();

        $saison_list = Shop_article::select('saison')->distinct('name')->get();

        return view('Articles/Create_article_lesson',compact('requete_article','requete_cate','saison_list','requete_prof')) ;
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

   
      return redirect()->route('create_article_member')->with('success', 'article a été crée avec succès');
       
    

    }


    public function inserer_article_produit(Request $request){

       
          $article  = new Shop_article;
   
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
  
        
          // recupere l'id de l'article qu'on a juste cree
          $requete_article = Shop_article::select('id_shop_article')->orderBy('created_at', 'desc')->first();
  
  
      //----------------------------------------- inserer les infos dans la table Produit --------------------------- //
      
         // on appelle le modele shop_article_2
         $requete_produit = new shop_article_2;
         $requete_produit->id_shop_article  =  $requete_article["id_shop_article"];
  
        $tab_XS = [] ;
        $tab_S  = [] ;
        $tab_L  = [] ;
        $tab_M  = [] ;

        // taille XS
        $tab_XS = [
            "Stock_ini" => $request->input('stock_ini_xs'),
            "Stock_actuel" => $request->input('stock_actu_xs')
        ] ;
        $tab_S = [
            "Stock_ini" => $request->input('stock_ini_s'),
            "Stock_actuel" => $request->input('stock_actu_s')
        ];
        $tab_M = [
            "Stock_ini" => $request->input('stock_ini_m'),
            "Stock_actuel" => $request->input('stock_actu_m')
        ];
        $tab_L = [
            "Stock_ini" => $request->input('stock_ini_l'),
            "Stock_actuel" => $request->input('stock_actu_l')
        ];


         $input_declinaison = [
              "XS" => $tab_XS,
              "S" => $tab_S,
              "L" =>  $tab_L,
              "M" =>  $tab_M          
      ];

    
         $requete_produit->declinaison =  json_encode($input_declinaison,JSON_NUMERIC_CHECK);
        
         
         $requete_produit->save();
  
     
        return redirect()->route('create_article_produit')->with('success', 'article a été crée avec succès');
         
      
  
  
  
      }


 
      public function inserer_article_lesson(Request $request){

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
      
         
            return redirect()->route('create_article_lesson')->with('success', 'article a été crée avec succès');
             
          
      
          }
      








































    public function test(Request $request){
        
        $article  = new Shop_article;
        
        $article->saison = $request->input('saison');
        $article->title  = $request->input('title');
        $article->image  = $request->input('image');

        $a = $request->editor1;
        $json = $request->prof;


        $shopservice = new shop_article_1;
        $shopservice->teacher = $request->prof;

       // $b = $request->resume ;

        //$b  = implode(',',$request->startdate) ;
        return $a;
    }



// SUPPRESSION D'ARTICLES

    public function delete($id)
    {
        
        $Shop_article = Shop_article::where('id_shop_article', $id)->delete();
        $shop_article_1 = shop_article_1::where('id_shop_article', $id)->delete();
        $shop_article_0 = shop_article_0::where('id_shop_article', $id)->delete();
        $shop_article_2 = shop_article_2::where('id_shop_article', $id)->delete();
      
        return redirect()->route('index_article')->with('success', 'article a été supprimé avec succès');

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
        $room = rooms::get();
      

        return view('Articles/edit_index',compact('Shop_article','shop_article_1','shop_article_0','requete_cate','saison_list','requete_prof','Id','room'));

    }

    public function edit(Request $request, $id){

/*
        $this->validate($request, [
                
        
            'saison'        => 'required',
            'title'         => 'required',
            'image'         => 'required',
            'ref'           => 'required',
            'nouveaute'    => 'required',
            'startvalidity'  => 'required',
            'endvalidity'  => 'required',
            'need_member' => 'required',
             'agemin'       => 'required',
             'agemax'      => 'required',
            'price'        => 'required',
            'price_indicative'   => 'required',
           
            'stock_ini'         => 'required',
            'stock_actuel'       => 'required',
            'alert_stock'         => 'required',
            'type_article'        => 'required',
            'max_per_user'        => 'required',
            'short_description'   => 'required',
            'description'      => 'required',

      
          ]);

          */
       
        Shop_article::where('id_shop_article', '=', $id)
        ->update([
            'saison'         => $request->saison ,
            'title'         => $request->input('title'),
            'image'         => $request->input('image'),
            'ref'           => $request->input('ref'),
            'nouveaute'     => $request->input('nouveaute'),
            'startvalidity' => $request->input('startvalidity'),
            'endvalidity' => $request->input('endvalidity'),
            'need_member' => $request->input('need_member'), 
             'agemin'       => $request->input('agemin'),
             'agemax'     =>  $request->input('agemax'),
            'price'        => $request->input('price'),
        'price_indicative' => $request->input('price_indicative'),
        'totalprice'       => 10,
        'stock_ini'         => $request->input('stock_ini'),
        'stock_actuel'      => $request->input('stock_actuel'),
        'alert_stock'       => $request->input('alert_stock'),
        'type_article'      => $request->input('type_article'),
        'max_per_user'      => $request->input ('max_per_user'),
        'short_description' => $request->input('short_description'),
        'description'      => $request->input('editor1') ,

        
        'afiscale' => $request->input('afiscale'),
        'sex_limit' => $request->input('sex_limit'),
         'selected_limit' => $request->input('strict' ),
        'categories' =>  json_encode($request->input('category'),JSON_NUMERIC_CHECK)

        
        
        ]);



        $tab_startdate = [] ;
        $tab_enddate =  [] ;

        // transformer les dates en format
        $tab_startdate = array_push($tab_startdate,$request->input('enddate')) ;
        $tab_enddate   = array_push($tab_enddate, $request->input('startdate')) ;


        $input_lesson = [
            "room" => (int)$request->input('room') ,
            "end_date" => $request->input('enddate'),
            "start_date" =>  $request->input('startdate')      
    ];
    $tab_stock_ini = array((int)$request->input('stock_ini')) ;
    $input_stock_ini = ["stock_ini" =>  $tab_stock_ini ];

$tab_stock_actuel = array((int)$request->input('stock_statut'));
$input_stock_actuel = ["stock_actuel" =>   $tab_stock_actuel ];



        shop_article_1::where('id_shop_article', '=', $id)
        ->update([
            'teacher'        => json_encode($request->input('prof'),JSON_NUMERIC_CHECK),
            'lesson'         => json_encode($input_lesson),
            'stock_ini'      => json_encode($input_stock_ini),
           'stock_actuel'    => json_encode($input_stock_actuel)
        ]);
           

   
        return redirect()->route('index_article')->with('success', 'l\'article a été modifié avec succès');



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
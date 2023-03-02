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
 
  $tab_libelle = [] ;
  $tab_stock_ini = [] ;
  $tab_stock_actu = [] ;
  $final_tab = [] ;

  foreach($request->libelle as $libelle){
     $tab_libelle[] = $libelle;
   
  }
  foreach($request->stock_ini as $val){
     $tab_stock_ini[] = $val;
 }
 foreach($request->stock_actuel as $val){
     $tab_stock_actu[] = $val; 
 }

 dd($tab_libelle) ;
 

 /* $i=0;  
  while ( $i < count($tab_libelle)-1 ) { 
     $final_tab = [
         '.$tab_libelle[$i].' => $tab_libelle[$i] ,
         'stock initial' => $tab_stock_ini[$i],
         'stock actuel' => $tab_stock_actu[$i]
     ] ;
         
     $i = $i + 1;
 }


  $requete_produit->declinaison =  json_encode($final_tab,JSON_NUMERIC_CHECK);
 
  dd($tab_stock_actu) ;
//  $requete_produit->save();


//return redirect()->route('create_article_produit')->with('success', 'article a été crée avec succès')->with('user', auth()->user());
  




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
      
*/























      
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
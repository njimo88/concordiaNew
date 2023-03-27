<?php

namespace App\Http\Controllers;

use App\Models\course_regular;
use App\Models\course_regular_user;
use App\Models\courses;
use App\Models\rooms;
use App\Models\Shop_article;
use App\Models\Shop_category;
use App\Models\shop_article_1;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Basket;
use App\Models\shop_article_2;






require_once(app_path().'/fonction.php');


class A_Controller_categorie extends Controller
{
    //
    //---------------------------------------------------------------------Backoffice method pour le shop ----------------'

    public function index()
    {
       // $a_requete = Shop_category::get();

      //  $info = Shop_category::where('id_shop_category_parent','=','0')->orderBy('order_category', 'ASC')->get();
      $info = [
        'categories' =>  Shop_category::where('id_shop_category_parent','=>','9')->orderBy('order_category', 'ASC')->get(),
    
            ];

        $shop_category =   Shop_category::get() ;

        MiseAjourStock();
       
        return view('A_Categorie', $info, compact('shop_category'))->with('user', auth()->user());

    }

    //boutton Payer
    public function Passer_au_paiement($id, Request $request)
    {

        
        
    $shop = Shop_article::where('id_shop_article', $id)->firstOrFail();
    //step 1 : Mise à jour de stock de l'article
    MiseAjourArticle($shop);

    $id_article = $shop->id_shop_article;
    $need_member = $shop->need_member;
    $selected_user_id = $request->selected_user_id;
    $quantite = $request->qte;
    $declinaison = $request->declinaison;
    //verifier si l'article est en stock et les conditions d'achat
    //$quantite = $request->quantite;
    
    if(verifierStockUnArticle($shop, $quantite) && countArticle($selected_user_id,$id_article) < $shop->max_per_user){
    
    
    
        
        // Vérifier si le produit est déjà dans le panier pour ce user
        $panier = Basket::where([
            ['user_id', auth()->user()->user_id],
            ['pour_user_id', $selected_user_id],
            ['ref', $id_article],
            ['declinaison', $declinaison],
        ])->first();

        if ($panier) {
            // Si le produit est déjà dans le panier, mettre à jour la quantité
            $panier->qte += $quantite;
            $panier->save();
        } else {
            // Ajouter une nouvelle ligne pour le produit
            $addcommand = new Basket();
            $addcommand->user_id = auth()->user()->user_id;
            $addcommand->family_id = auth()->user()->family_id;
            $addcommand->pour_user_id = $request->selected_user_id;
            $addcommand->ref = $shop->id_shop_article;
            $addcommand->qte = $quantite;
            $addcommand->declinaison = $declinaison;
            $addcommand->save();
        }
        

    if ($need_member != 0) { 
            $result = MiseAuPanier($selected_user_id, $id_article);
            if ($result == 0) {
                return redirect()->route('panier');}
            elseif ($result == $need_member) {
            $addcommand = new Basket();
            $addcommand->user_id = auth()->user()->user_id;
            $addcommand->family_id = auth()->user()->family_id;
            $addcommand->pour_user_id = $request->selected_user_id;
            $addcommand->ref = $need_member;
            $addcommand->qte = 1;
            $addcommand->save();

            return redirect()->route('panier');
        } else {
            //panier
            dd($result);
        }
    }

    return redirect()->route('panier');
}
    else{
        return redirect()->back()->with('error', 'Vous avez atteint la limite d\'achat de cet article ou il n\'est plus disponible');
    }
       
    }
    
//boutton commander
public function commander_article($id, Request $request)
{
    $shop = Shop_article::where('id_shop_article', $id)->firstOrFail();
    MiseAjourArticle($shop);

    //step 1 : Mise à jour de stock de l'article

    $id_article = $shop->id_shop_article;
    $need_member = $shop->need_member;
    $selected_user_id = $request->selected_user_id;
    $quantite = $request->qte;
    $declinaison = $request->declinaison;
    
    //verifier si l'article est en stock et les conditions d'achat
    //$quantite = $request->quantite;
    if(verifierStockUnArticle($shop, $quantite) && countArticle($selected_user_id,$id_article) < $shop->max_per_user){

        // Vérifier si le produit est déjà dans le panier pour ce user
        $panier = Basket::where([
            ['user_id', auth()->user()->user_id],
            ['pour_user_id', $selected_user_id],
            ['ref', $id_article],
            ['declinaison', $declinaison],
        ])->first();

        if ($panier) {
            // Si le produit est déjà dans le panier, mettre à jour la quantité
            $panier->qte += $quantite;
            $panier->save();
        } else {
            // Ajouter une nouvelle ligne pour le produit
            $addcommand = new Basket();
            $addcommand->user_id = auth()->user()->user_id;
            $addcommand->family_id = auth()->user()->family_id;
            $addcommand->pour_user_id = $request->selected_user_id;
            $addcommand->ref = $shop->id_shop_article;
            $addcommand->qte = $quantite;
            $addcommand->declinaison = $declinaison;
            $addcommand->save();
        }

        if ($need_member != 0) { 
            $result = MiseAuPanier($selected_user_id, $id_article);

            if ($result == 0) {
                return redirect()->back()->with('success', 'Article ajouté au panier');
            } elseif ($result == $need_member) {
                $addcommand = new Basket();
                $addcommand->user_id = auth()->user()->user_id;
                $addcommand->family_id = auth()->user()->family_id;
                $addcommand->pour_user_id = $request->selected_user_id;
                $addcommand->ref = $need_member;
                $addcommand->qte = 1;
                $addcommand->save();
                return redirect()->back()->with('success', 'Article ajouté au panier');
            } else {
                //panier
                dd($result);
            }
        }

        return redirect()->back()->with('success', 'Article ajouté au panier');
    } else {
        return redirect()->back()->with('error', 'Vous avez atteint la limite d\'achat de cet article ou il n\'est plus en stock');
    }
}



    public function commanderModal($shop_id,$user_id, Request $request)
    {
        MiseAjourStock();
        $declinaison = $request->input('declinaison');
        $qte = $request->input('qte');
        $selected_user = $user_id;
        $shop = Shop_article::where('id_shop_article', $shop_id)->firstOrFail();
        return view('Articles.modal.commanderModal', compact('shop','user_id','qte','declinaison'))->with('user', auth()->user());
    }

    public function saveNestedCategories(Request $request){
        
        $json = $request->nested_category_array;
        $decoded_json = json_decode($json, TRUE);

        $simplified_list = [];
        $this->recur1($decoded_json, $simplified_list);

        // -------- here the issue ------------

      
       
            foreach($simplified_list as $k => $v){
                Shop_category::where('id_shop_category', '=', $v['id_shop_category'])->update([
                    
                    "id_shop_category_parent" => $v['id_shop_category_parent'],  
                    "order_category" => $v['order_category'] 
                ]);
                             
            }

            
             
     


      return redirect(route('A_Categorie'));
        //print_r($simplified_list);
    }

   

    public function recur1($nested_array=[], &$simplified_list=[]){
        
        static $counter = 0;
        
        foreach($nested_array as $k => $v){
            
            $order_category = $k+1;
            $simplified_list[] = [
                "id_shop_category" => $v['id'], 
                "id_shop_category_parent" => 0,
                "order_category" =>  $order_category
            ];
            
            if(!empty($v["children"])){
                $counter+=1;
                $this->recur2($v['children'], $simplified_list, $v['id']);
            }

        }
    }


    public function recur2($sub_nested_array=[], &$simplified_list=[], $parent_id = NULL){
        
        static $counter = 0;

        foreach($sub_nested_array as $k => $v){
            
            $order_category = $k+1;
            $simplified_list[] = [
                "id_shop_category" => $v['id'], 
                "id_shop_category_parent" => $parent_id, 
                "order_category" => $order_category
            ];
            
            if(!empty($v["children"])){
                $counter+=1;
                return $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }



    public function create(Request $request){


        $this->validate($request, [
            'nom' => 'required|max:255|alpha',
            'image' => 'required|max:255',
            'description' => 'required|max:255'

        ]);
      

        $category  = new Shop_category ;
        $category->name = $request->input('nom');
        $category->image = $request->input('image');
        $category->description = $request->input('description');

        if ($request->input('action') == "new_cat"){
        

        
            $category->id_shop_category = $request->input('id');
            $category->id_shop_category_parent = 0;
            $category->order_category = $request->input('id');

            if($request->has('active')){
                $category->active = $request->input('active') ;
            }else{
                $category->active = 0 ;
            }

            

        }
        else{

            $requete_order = Shop_category::select('order_category')->where("id_shop_category","="," $request->input('action') ")->get() ;
           
            $category->id_shop_category =  $request->input('id');
            $category->id_shop_category_parent = (int)$request->input('action');
            $category->order_category = 1;
            if($request->has('active')){
                $category->active = $request->input('active') ;
            }else{
                $category->active = 0 ;
            }
        

        }

       
      $category->save();
     return redirect(route('A_Categorie'));

     

    }
    


    public function remove(Request $request, $id){

        $category  = Shop_category::where('id_shop_category', $id)->delete();
    
      return redirect(route('index_article'))->with('success', "Categorie supprimée.");
    }



    public function edit_index(Request $request, $id){

        
            //'categories' => Shop_category::orderBy('name', 'ASC')->get(),
            $info = Shop_category::where('id_shop_category', $id)->get();
            $shop_category = Shop_category::where('id_shop_category', $id)->get();
            $shop_article = Shop_article::get();

            return view('Category_modify',compact('shop_article','info','shop_category'))->with('user', auth()->user());
    }








   /*
    public function store(Request $request){
        $rules=[
            'name' => 'required',
            'parent_id' => 'nullable',
        ];

        $messages = [
            "name.required" => "Category name is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        
        if( $validator->fails() ){
            return back()->withErrors($validator)->withInput();
        } else {
            DB::beginTransaction();
            try {
                $info = [
                    "success" => FALSE,
                ];
                $query = [
                    'name' => $request->name,
                    'parent_id' => (!empty($request->parent_id))? $request->parent_id : 0,
                ];
    
                $category = CategoryModel::updateOrCreate(['category_id' => $request->category_id], $query);

                DB::commit();
                $info['success'] = TRUE;
            } catch (\Exception $e) {
                DB::rollback();
                $info['success'] = FALSE;
            }

            if(!$info['success']){
                return redirect(route('category-subcategory.create'))->with('error', "Failed to save.");
            }

            return redirect(route('category-subcategory.edit', ['category_id' => $category->category_id]))->with('success', "Successfully saved.");
        }
    }



*/

//---------------------------------------------------------------------frontoffice method pour le shop -------------------------------------------------------'
public function MainShop()
{
    
    $info = Shop_category::where('id_shop_category', '<=', '9')->orderBy('order_category', 'ASC')->get();

    
    $systemSetting = SystemSetting::find(3);
    $messageContent = null;

    if ($systemSetting && $systemSetting->value == 1) {
        $messageContent = $systemSetting->Message;
    }

    return view('A_Shop_Categorie_index', compact('info', 'messageContent'))->with('user', auth()->user());
}



// Methode qui permet l'affichage du contenu des sous categories
public function  Shop_souscategorie($id){

    MiseAjourStock();

    $indice = $id; // indice de la categorie qu'on recupere apres click
    $info_parent =   Shop_category::select('id_shop_category_parent','name')->get() ;
    $info =   Shop_category::get() ;
    $info2 =  Shop_category::select('name','description')->where('id_shop_category','=',$indice)->first() ;

    $article = Shop_article::get();
    $shopService =  shop_article_1::get();
    $rooms = rooms::get();
    $a_user = User::all();



    // jointure des tables shop_article, liaison_shop_articles_shop_categories et shop categorie pour l'affichage des categories et de leur descentantes(categories)
    $saison_active = saison_active();

    $n_var = DB::table('shop_article')
        ->join('liaison_shop_articles_shop_categories', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_shop_categories.id_shop_article')
        ->join('shop_category', 'shop_category.id_shop_category', '=', 'liaison_shop_articles_shop_categories.id_shop_category')
        ->select('shop_article.*','shop_category.*',  'shop_article.image as image')
        ->where('shop_article.saison', '=', $saison_active)
        ->get();

        
    $n_var = filterArticlesByValidityDate($n_var);
    $requete = getFilteredArticles($n_var); 


    $systemSetting = SystemSetting::find(3);
    $messageContent = null;

    if ($systemSetting && $systemSetting->value == 1) {
        $messageContent = $systemSetting->Message;
    }

    return view('A_Shop_SousCategorie_index',compact('info','info_parent','messageContent','indice','requete','info2','article','shopService','rooms','a_user'))->with('user', auth()->user());

}


public function Handle_details($id){
    MiseAjourStock();

    $articl= Shop_article::where('id_shop_article', $id)->firstOrFail();
    if ($articl->type_article == 2) {
        $déclinaison = shop_article_2::where('id_shop_article', $id)->select('declinaison')->firstOrFail();
        $declinaisons = json_decode($déclinaison->declinaison, true);
    }
    
    $indice = $id;
    $article = Shop_article::get();
    $shopService =  shop_article_1::get();
    $rooms = rooms::get();
    $a_user = User::get();
    $info =   Shop_category::get();
    $selectedUsers = array();

    $systemSetting = SystemSetting::find(3);
    $messageContent = null;

    if ($systemSetting && $systemSetting->value == 1) {
        $messageContent = $systemSetting->Message;
    }

    // Convertir la chaîne JSON en tableau PHP
    if (Auth::check()) {
        $selectedUsers = getArticleUsers($articl);
    }
    if ($articl->type_article == 2) {
        return view('Details_article', compact('indice', 'messageContent','article', 'rooms', 'shopService', 'a_user', 'selectedUsers','info','declinaisons'))->with('user', auth()->user());

    }
    return view('Details_article', compact('indice','messageContent', 'article', 'rooms', 'shopService', 'a_user', 'selectedUsers','info'))->with('user', auth()->user());
}




/* methode pour qui creer un JSON  et remplit le champ teacher de la table shop_article_1  */
   
public function  JsonProcess(){

                            $tab1 = array();
                            $tab2 = array();
                            $tab3 = array();

                            $request1 = course_regular::get();
                            $request2 = course_regular_user::get();
                            $request3 = shop_article_1::get();

                        
                            foreach ($request3 as $data3) {

                                            foreach ($request1 as $data1) {
                                            
                                                    if ($data1->id_shop_article == $data3->id_shop_article){
                                                                    
                                    
                                                                array_push($tab1,$data1->id_user);
                                                                    
                                                                
                                                    }

                                                }
                                


                                            foreach ($request2 as $data2) {
                                            
                                                        if ($data2->id_shop_article == $data3->id_shop_article){
                                                                    
                                    
                                                                array_push($tab2,$data2->id_user);
                                                                    
                                                                
                                                        } 
                                        
                                        }
                                        
                                   
                                  
                                        print("=================[.$data3->id_shop_article.]==================<br>");
                                    $tab3 =  array_merge($tab1,$tab2);
                                    $tab3 = array_unique($tab3);
                                    print_r($tab3);
                                    
                                    DB::table('shop_article_1')
                                        ->where('id_shop_article',$data3->id_shop_article )
                                        ->update(['teacher' => $tab3]);

                                        $tab1 = array();
                                        $tab2 = array();
                                         $tab3 = array();
                                    
                                    

                    }


   }


   /* methode pour qui creer un JSON  a partir des elements de la table Courses (start_date , end_date, id_room) et remplir le champ
    lesson de la table shop_article_1 */
   
public function  JsonProcess2(){

    $tab1 = array();
    $tab2 = array();
    $tab3 = array();

    $request1 = courses::orderBy('id_shop_article', 'ASC')->get();
    $request3 = shop_article_1::get();


    foreach ($request3 as $data3) {

                    foreach ($request1 as $data1) {

                    
                            if ($data1->id_shop_article == $data3->id_shop_article){
                                            
                                        
                                        array_push($tab1,$data1->start_date);
                                        array_push($tab2,$data1->end_date);
                                        array_push($tab3,$data1->id_room);

                                        $json = array('start_date' => $tab1,
                                                    'end_date' => $tab2,
                                                    'room' => $tab3) ;                      
                            }
                            
                    }
        

          
               
           
           // $json = json_encode($json);
            print_r($json);
         

        DB::table('shop_article_1')
                ->where('id_shop_article',$data3->id_shop_article )
                ->update(['lesson' => $json]);
       
                $tab1 = array();
                $tab2 = array();
                $tab3 = array();
            

}

    }

 /* methode pour qui creer un JSON  et remplit le champ shop ini de la table shop_article_1 */

public function  JsonProcess3(){

    $tab1 = array();
    $tab2 = array();
    $tab3 = array();

    $request1 = Shop_article::orderBy('id_shop_article', 'ASC')->get();
    $request3 = shop_article_1::get();


    foreach ($request3 as $data3) {

                    foreach ($request1 as $data1) {

                    
                            if ($data1->id_shop_article == $data3->id_shop_article){
                                            
                                        
                                        array_push($tab1,$data1->stock_ini);
                                      
                                        $json = array('stock_ini' => $tab1) ;                      
                            }

                    }
        

          
                print("=================[.$data3->id_shop_article.]==================<br>");
           
           // $json = json_encode($json);
            print_r($json);
         

        DB::table('shop_article_1')
                ->where('id_shop_article',$data3->id_shop_article )
                ->update(['stock_ini' => $json]);
              
            $tab1 = array();
               
   
}

}

}   

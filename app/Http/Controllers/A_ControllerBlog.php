<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\A_Blog_Post;
use App\Models\A_Categorie1;
use App\Models\A_Categorie2;
use App\Models\liaison_blog_posts;
use App\Models\liaison_blog_terms;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\Generator\StringManipulation\Pass\Pass;
use PhpParser\Node\Stmt\Else_;
use App\Models\Shop_category;

class A_ControllerBlog extends Controller
{ 
   /* 
       public function Afficher(){
        $data = [
            'productOne' => 'iphone',
            'productTwo' => 'Samsung',
        ] ;

        $letitre = A_Blog_Post::select('titre')->get();

       

        $Nom = "cool";
        $a_infoblog = A_Blog_Post::get();

        return view('Blog_singlepage', compact('a_infoblog'));
        
        //return view('Blog_singlepage')->with('data',$data) ; // with the "wih method" the key and the $variable must be identical
    }
   
   

   public function a_Afficher_PagePrincipal(){


        $a_infoblog = A_Blog_Post::latest('date_post')->paginate(25,['*'],'A_Blog_index'); //there are also SortBy it will be Asc order 	->sortBy('code');
        $a_contenu = A_Blog_Post::select('contenu')->get();

       //$a_categorie1 = A_Blog_Post::select('categorie1')->get();
       $a_categorie1= A_Blog_Post::first()->get('categorie1');

     //   $a_contenu = A_ControllerBlog::htmlToPlainText($a_contenu) ;
    
        return view("A_Blog_index",[
            'a_infoblog' => $a_infoblog,
            'a_contenu' => $a_contenu,
            'a_categorie1' => $a_categorie1
        ]);
    }
             $a_post = A_Blog_Post::latest('date_post')->paginate(5);
          $a_post = A_Blog_Post::paginate(5);

          return view('Blog_test',compact('a_post'));
          
          */

    public function a_fetchPost(Request $request){
        
        $a_post = A_Blog_Post::latest('date_post')->paginate(3);
        $a_categorie1 = A_Categorie1::select('Id_categorie1','image')->get();
        $a_categorie2 = A_Categorie2::select('Id_categorie2','image')->get();
   // Check if request is ajax or not 
  // If request is ajax then we have to return the data of next page's content in json array
        if($request->ajax()) {
            return $data = [
                    "view" => view('A_Blog_scroll',compact('a_post','a_categorie1','a_categorie2'))->render(),
                    'url' => $a_post->nextPageUrl()
                    ];
            }


      return view('A_Blog_index',compact('a_post','a_categorie1','a_categorie2'))->with('user', auth()->user());
      

      
}

public function Simple_Post($id){
    $a_post = A_Blog_Post::find($id);
    $a_categorie1 = A_Categorie1::select('Id_categorie1','image')->get();
    $a_categorie2 = A_Categorie2::select('Id_categorie2','image')->get();
    return view('club.Simple_Post',compact('a_post','a_categorie1','a_categorie2'))->with('user', auth()->user());
}
public function recherche_par_cat1(Request $request, $id) {

    // $a_requete1 = A_Blog_Post::latest('date_post')->paginate(5);
    $a_requete1 = A_Blog_Post::latest('date_post')->paginate(5000);
    $a_categorie1 = A_Categorie1::select('Id_categorie1','image')->get();
    $a_categorie2 = A_Categorie2::select('Id_categorie2','image')->get();
   
    $a_result = $id ;



    return view('A_blog_par_categorie1', compact('a_requete1','a_result','a_categorie1','a_categorie2'))->with('user', auth()->user()) ;
   


    }
   

public function recherche_par_cat2(Request $request, $id) {


    $a_requete1 = A_Blog_Post::latest('date_post')->paginate(1000);
    $a_categorie1 = A_Categorie1::select('Id_categorie1','image')->get();
    $a_categorie2 = A_Categorie2::select('Id_categorie2','image')->get();
 
   
    $a_result = $id ;



    return view('A_blog_par_categorie2', compact('a_requete1','a_result','a_categorie1','a_categorie2'))->with('user', auth()->user()) ;
    
}


  






 public function a_requetes1(Request $request){

        $cat1 = array();
        $cat2 = array();
        $request1  = A_Blog_Post::select('id_blog_post_primaire')->get();
        $request2  = liaison_blog_posts::select('id_blog_post','id_blog_category')->get();
        $request3  = liaison_blog_terms::select('id_blog_post','id_term')->get();
       
      
        foreach ($request1 as $data) {
           
           $cat2 = [] ;
           $a_ma_requete = A_Blog_Post::find($data->id_blog_post_primaire);
           //print_r($a_ma_requete->id_blog_post_primaire." | ");
           array_push($cat1,$a_ma_requete->id_blog_post_primaire );

           foreach($request2 as $data2){
            if ($data->id_blog_post_primaire == $data2->id_blog_post){
                array_push($cat2,$data2->id_blog_category );

            }

           }
           $cat2 = array_unique($cat2);
           $cat2 = json_encode($cat2);
           $a_ma_requete->categorie1 = $cat2;
           $a_ma_requete->save();

         

        }
        
      
    print_r($cat2);
        
        
    
    }



    public function a_requetes2(Request $request){

        $cat1 = array();
        $cat2 = array();
        $request1  = A_Blog_Post::select('id_blog_post_primaire')->get();
        $request2  = liaison_blog_posts::select('id_blog_post','id_blog_category')->get();
        $request3  = liaison_blog_terms::select('id_blog_post','id_term')->get();
       
      
        foreach ($request1 as $data) {
           
           $cat2 = [] ;
           $a_ma_requete = A_Blog_Post::find($data->id_blog_post_primaire);
           //print_r($a_ma_requete->id_blog_post_primaire." | ");
           array_push($cat1,$a_ma_requete->id_blog_post_primaire );

           foreach($request3 as $data3){


                    if ($data->id_blog_post_primaire == $data3->id_blog_post){
                        array_push($cat2,$data3->id_term );

                    }

           }
           $cat2 = array_unique($cat2);
           $cat2 = json_encode($cat2);
           $a_ma_requete->categorie2 = $cat2;
           $a_ma_requete->save();

         

        }
        
      
    print_r($cat2);
        
        
    
    }



}
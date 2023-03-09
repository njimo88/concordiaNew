<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\A_Blog_Post;
use App\Models\A_Categorie1;
use App\Models\A_Categorie2;
use App\models\Shop_article;
use App\models\User;
use App\Models\Shop_category;
use Illuminate\Http\Request;

class BlogArticle_Controller extends Controller
{
    //

    function index()
    {
        $requete_article = Shop_article::paginate(30) ;
        $requete_blog  =   A_Blog_Post::paginate(30) ;
        //$requete_user  =   User::select('user_id','name')->get();
         $requete_user = DB::table('users')
        ->join('blog_posts', 'blog_posts.id_user', '=', 'users.user_id')->paginate(30);

        return view('BlogArticle_Backoffice/BlogArticle_index',compact('requete_blog','requete_article','requete_user'))->with('user', auth()->user()) ;

    }

    function index_article_redaction()
    {
        return 'a';
    }

    function index_article_category(){

        $requete_categorie1 = A_Categorie1::paginate(30) ;
        $requete_categorie2 = A_Categorie2::paginate(30) ;

        return view('BlogArticle_Backoffice/BlogArticle_index_category',compact('requete_categorie1','requete_categorie2'))->with('user', auth()->user())  ;

    }

    function index_creation_cate($type_cate){

        $cate = $type_cate ;
       
       
     return view('BlogArticle_Backoffice/BlogArticle_creation_categorie',compact('cate'))->with('user', auth()->user())  ;
        }
       
       
    
       
    
    function create_cate(Request $request, $cate){
        
      /*
        $validatedData = $request->validate( [
            'title' => 'required|numeric',
            'title'  =>  ['required', 'alpha', 'max:255'],
            'image'  =>  ['required', 'alpha', 'max:255'],
             'ref'  =>  ['required', 'alpha', 'max:255'],

    
        ], $messages = [
            'title.required' => "Le champ titre est requis.",
            'title.max' => "Le titre ne doit pas dépasser 255 caractères.",
            'image.required' => "Le champ image est requis.",
            'image.alpha' => "l'image doit être une chaîne de caractères.",
            

        ]);
       
    
        */
        if ($cate == 1){

            $cate1  = new A_Categorie1();
   
 
            $cate1->nom_categorie = $request->input('titre');
            $cate1->image  = $request->input('image');
            $cate1->categorie_URL = '' ;
            $cate1->visibilite  = $request->input('visibilite');
            $cate1->description   = $request->input('description');


            $cate1->save();

            return redirect()->route('index_creation_cate',0)->with('user', auth()->user())->with('success', 'la nouvelle catégorie a été crée avec succès');

          
        }
        else{
            $cate2  = new A_Categorie2();
            $cate2->nom_categorie = $request->input('titre');
            $cate2->image  = $request->input('image');
            $cate2->categorie_URL = '' ;
            $cate2->description   = $request->input('description');


            $cate2->save();

            return redirect()->route('index_creation_cate',2)->with('user', auth()->user())->with('success', 'la nouvelle catégorie a été crée avec succès');


 

        }
      
   
      

       // return view('BlogArticle_Backoffice/BlogArticle_creation_categorie')->with('user', auth()->user())  ;
       return $cate;
    }


    // SUPPRESSION 

 function delete($id)
    {
        
        $cate1 = A_Categorie1::where('Id_categorie1', $id)->delete();

        $cate2 = A_Categorie2::where('Id_categorie2', $id)->delete();

      
      //  return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');
      return redirect()->back()->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');

    }

// Edition

function edit_index($id){

    $Id =$id;
    $cate1 = A_Categorie1::where('Id_categorie1', $id)->get();
    $cate2 = A_Categorie2::where('Id_categorie2', $id)->get();

    return view('BlogArticle_Backoffice/index_category_edit',compact('cate1','cate2','Id'))->with('user', auth()->user());


}

function edit_cate(Request $request, $id){

    $cate1=A_Categorie1::find($id);
    $cate2=A_Categorie2::find($id);

    
    if ($cate1){
        $cate1->update($request->all());
    }
  
    if ($cate2){
        $cate2->update($request->all());
    }
    
    return redirect()->route('index_article_category')->with('user', auth()->user())->with('success', 'article a été modifié avec succès');

}


}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\A_Blog_Post;
use App\Models\Shop_article;
use App\Models\User;
use App\Models\Category;
use App\Models\Shop_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogArticle_Controller extends Controller
{
    //

    function index()
    {
        $requete_article = Shop_article::All() ;
        $requete_blog  =   A_Blog_Post::All()  ;
        $requete_user = DB::table('users')
        ->join('blog_posts', 'blog_posts.id_user', '=', 'users.user_id')
        ->orderBy('blog_posts.date_post', 'desc') 
        ->select('users.lastname','users.name','users.user_id','users.color','blog_posts.*')
        ->get();

        $userNamesAndIds = $requete_user->map(function ($user) {
            return [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'color' => $user->color,
            ];
        })->unique('user_id') 
        ->values(); 
        
        return view('BlogArticle_Backoffice/BlogArticle_index',compact('userNamesAndIds','requete_blog','requete_article','requete_user'))->with('user', auth()->user()) ;

    }



    function edit_blog_index($id){
        $Id = $id ;
        $blog  =  A_Blog_Post::where('id_blog_post_primaire', $id)->get();
        $Categorie1 = Category::whereBetween('id_categorie', [100, 199])->get();
        $Categorie2 = Category::whereBetween('id_categorie', [200, 299])->get();
        
        return view('BlogArticle_Backoffice/BlogArticle_edit_blog',compact('blog','Categorie2','Categorie1','Id'))->with('user', auth()->user()) ;

    }
    function edit_color_user(Request $request){
        $request->validate([
            'color' => 'required|string|max:7', // Validate hex color code
        ]);
        if($request->user_id=="null")
        {
            return redirect()->back()->with('user', auth()->user());
        }
        //update the color 
        DB::table('users')
        ->where('user_id', $request->user_id)
        ->update(['color' => $request->color]);
        return redirect()->back()->with('user', auth()->user())->with('success', 'la couleur été modifier avec succès');
    }

    function edit_blog($id, Request $request){

        $blog = A_Blog_Post::find($id); 
       
        if(isset($request->category1)){
                                 
            $blog->categorie =  json_encode($request->category1,JSON_NUMERIC_CHECK);
           
            $blog->save();  
        }
        
        if(isset($request->category2)){
                                 
            $blog->categorie =  json_encode($request->category2,JSON_NUMERIC_CHECK);
           
            $blog->save();  
        }

        if(isset($request->date_post)){
                                 
            $blog->date_post = $request->date_post;
           
            $blog->save();  
        }

        if(isset($request->editor1)){
                                 
            $blog->contenu = $request->editor1;
           
            $blog->save();  
        }

        
        switch($request->valider) {

         
            case 'Brouillon': 
                $blog->status = "Brouillon" ;
                $blog->save();  
                break;

            case 'publier': 
               
                    $blog->status = "Publié" ;
                    $blog->save();  
                    break;
                    
        }


        $blog->update($request->all());
        return redirect()->back()->with('user', auth()->user())->with('success', 'le billet de blog modifié a été avec succès');

       

    }



    function delete_blog($id)
    {
        
        $blog  =  A_Blog_Post::where('id_blog_post_primaire', $id)->delete();
  
      //  return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');
         return redirect()->back()->with('user', auth()->user())->with('success', 'le billet de blog a été supprimé avec succès');

    }



    function index_article_redaction()
    {
        $Categorie1 = Category::whereBetween('id_categorie', [100, 199])->get();
        $Categorie2 = Category::whereBetween('id_categorie', [200, 299])->get();

        return view('BlogArticle_Backoffice/BlogArticle_redaction_blog',compact('Categorie1','Categorie2'))->with('user', auth()->user()) ;

    }

    function creation_article_blog(Request $request){

    
        $Blog  = new A_Blog_Post() ;

        $Blog->id_user = auth()->user()->user_id;
        $Blog->id_last_editor = auth()->user()->user_id;
        $Blog->date_post = $request->input('date_post');
        $Blog->titre = $request->input('titre');
        $Blog->contenu = $request->input('editor1');
        $Blog->categorie = json_encode($request->input('category1'),JSON_NUMERIC_CHECK);
        $Blog->categorie = json_encode($request->input('category2'),JSON_NUMERIC_CHECK);

        

        switch($request->valider) {

         
            case 'Brouillon': 
                $Blog->status = "Brouillon" ;
                
                break;

            case 'publier': 
               
                    $Blog->status = "Publié" ;
                    break;
                    
        }

        $Blog->save();
        return redirect()->back()->with('user', auth()->user())->with('success', ' le nouvel article de blog a été creé avec succès');

    }

   



   



    function index_article_category(){

        $requete_categorie1  = Category::whereBetween('id_categorie', [100, 199])->paginate(30);
        $requete_categorie2 = Category::whereBetween('id_categorie', [200, 299])->paginate(30);
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

            $cate1  = new Category();
   
 
            $cate1->nom_categorie = $request->input('titre');
            $cate1->image  = $request->input('image');
            $cate1->categorie_URL = '' ;
            $cate1->visibilite  = $request->input('visibilite');
            $cate1->description   = $request->input('description');


            $cate1->save();

            return redirect()->route('index_creation_cate',0)->with('user', auth()->user())->with('success', 'la nouvelle catégorie a été crée avec succès');

          
        }
        else{
            $cate2  = new Category();
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
        
        $cate1 = Category::where('Id_categorie', $id)->delete();

        $cate2 = Category::where('Id_categorie', $id)->delete();

      
      //  return redirect()->route('index_article')->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');
      return redirect()->back()->with('user', auth()->user())->with('success', 'article a été supprimé avec succès');

    }

// Edition

function edit_index($id){

    $Id =$id;
    $cate1 = Category::where('Id_categorie', $id)->get();
    $cate2 = Category::where('Id_categorie', $id)->get();
    
    return view('BlogArticle_Backoffice/index_category_edit',compact('cate1','cate2','Id'))->with('user', auth()->user());


}

function edit_cate(Request $request, $id){

    $cate1=Category::find($id);
    $cate2=Category::find($id);

    
    if ($cate1){
        $cate1->update($request->all());
    }
  
    if ($cate2){
        $cate2->update($request->all());
    }
    
    return redirect()->route('index_article_category')->with('user', auth()->user())->with('success', 'article a été modifié avec succès');

}


}

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
use Illuminate\Support\Facades\DB;


require_once(app_path().'/fonction.php');

class A_ControllerBlog extends Controller
{ 
   public function questionnaire(){
    $param = DB::table('parametre')->where('activate', 1)->get()->toArray();

    $scoreQ1 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 1)->get()->toArray();
    $scoreQ2 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 2)->get()->toArray();
    $scoreQ3 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 3)->get()->toArray();
    $scoreQ4 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 4)->get()->toArray();
    $scoreQ5 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 5)->get()->toArray();
    $scoreQ6 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 6)->get()->toArray();
    $scoreQ7 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 7)->get()->toArray();
    $scoreQ8 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 8)->get()->toArray();
    $scoreQ9 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 9)->get()->toArray();
    $scoreQ10 = DB::table('sectionjeunereponses')->select('GAM', 'GAF', 'GAc', 'GR', 'AER', 'CrossTraining', 'Parkour')->where('id_q', 10)->get()->toArray();

    $quadall = DB::table('sectionadultesquestions')->get()->toArray();
    $quad = DB::table('sectionadultesquestions')->where('id_q', '<>', 1)->get()->toArray();

    foreach ($quadall as $quadall) {
        $ididid = $quadall->id_q;
        $basename = "scoread" . $quadall->id_q;
        $data["{$basename}"] = DB::select("SELECT * FROM sectionadultesreponses WHERE id_q = ?", [$ididid]);
    
    }
    
    $adQgen = DB::select("SELECT * FROM sectionadultesreponses WHERE id_q != 1 ORDER BY id_rep ASC");
    $adQgenmax = DB::select("SELECT * FROM sectionadultesquestions WHERE id_q != 1 ORDER BY id_q desc LIMIT 1");
    $idmax = $adQgenmax[0]->id_q;
    
    foreach ($quad as $quadratique) {
        $idq = $quadratique->id_q;
    };
    
    $adcat = DB::select("SELECT * FROM sectionadultecat");
    
    return view('questionnaire', compact('param', 'quad', 'adQgen', 'adQgenmax', 'idmax', 'adcat', 'scoreQ1', 'scoreQ2', 'scoreQ3', 'scoreQ4', 'scoreQ5', 'scoreQ6', 'scoreQ7', 'scoreQ8', 'scoreQ9', 'scoreQ10','quadall','quad', 'data', 'idq', 'ididid', 'basename'))->with('user', auth()->user());

   }

   

   public function anniversaire(){

    $usersbirth = getUsersBirthdayToday();

    return view('anniversaire', compact('usersbirth'))->with('user', auth()->user());
   }


   public function countdeterminesection(Request $request)
{
    $count = $request->input('count');
    DB::table('parametre')->where('activate', 1)->increment('determinesection', 1);
}

    public function a_fetchPost(Request $request){
        $a_post = A_Blog_Post::latest('date_post')->paginate(3);
        $a_categorie1 = A_Categorie1::select('Id_categorie1','image')->get();
        $a_categorie2 = A_Categorie2::select('Id_categorie2','image')->get();
        $post = DB::table('blog_posts')
        ->join('system', 'blog_posts.id_blog_post_primaire', '=', 'system.value')
        ->where('system.id_system', '=', 6)
        ->where('status','Publié')
        ->select('blog_posts.contenu')
        ->first();

        


   // Check if request is ajax or not 
  // If request is ajax then we have to return the data of next page's content in json array
        if($request->ajax()) {
            return $data = [
                    "view" => view('A_Blog_scroll',compact('a_post','a_categorie1','a_categorie2'))->render(),
                    'url' => $a_post->nextPageUrl()
                    ];
            }

            printUsersBirthdayOnImage();
      return view('A_Blog_index',compact('a_post','a_categorie1','a_categorie2','post'))->with('user', auth()->user());
}

public function Simple_Post($id){
    $a_post = A_Blog_Post::find($id);
    $a_categorie1 = A_Categorie1::select('Id_categorie1','image')->get();
    $a_categorie2 = A_Categorie2::select('Id_categorie2','image')->get();
    return view('club.Simple_Post',compact('a_post','a_categorie1','a_categorie2'))->with('user', auth()->user());
}
public function recherche_par_cat1(Request $request, $id) {

    // $a_requete1 = A_Blog_Post::latest('date_post')->paginate(5);
    $a_requete1 = A_Blog_Post::latest('date_post')->paginate(6);
    $a_categorie1 = A_Categorie1::select('Id_categorie1','image')->get();
    $a_categorie2 = A_Categorie2::select('Id_categorie2','image')->get();
   
    $a_result = $id ;



    return view('A_blog_par_categorie1', compact('a_requete1','a_result','a_categorie1','a_categorie2'))->with('user', auth()->user()) ;
   


    }
   

public function recherche_par_cat2(Request $request, $id) {


    $a_requete1 = A_Blog_Post::latest('date_post')->paginate(6);
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
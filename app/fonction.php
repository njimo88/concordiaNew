<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\shop_article;
use App\Models\shop_article_1;
use App\Models\LiaisonShopArticlesBill;
use App\Models\Shop_category;
use Illuminate\Support\Facades\Mail;





 function findFamilyIdByUserId($id_user) {
    return DB::table('users')->where('user_id', $id_user)->value('family_id');
}


//fonctions pour afficher les dates
function fetchDayy($date){
    $lejour = ( new DateTime($date) )->format('l');

  $jour_semaine = array(
"lundi" => "Monday",
"Mardi" => "Tuesday",
"Mercredi" => "Wednesday",
"Jeudi" => "Thursday",
"Vendredi" => "Friday",
"Samedi" => "Saturday",
"Dimanche" => "Sunday"

  );

}


function isUserMember($user_id)
{
    $saison = saison_active();

    // Récupérer tous les articles achetés par l'utilisateur au cours de la saison active
    $articles = Donne_articles_Paye_d_un_user_aucours_d_une_saison($user_id, $saison);

    // Récupérer tous les articles de la table shop_article_0
    $article_0 = $n_var = DB::table('shop_article')
    ->select('shop_article.id_shop_article', 'shop_article.totalprice')
    ->where('shop_article.saison', '=', $saison)
    ->where('shop_article.type_article', '=', '0')
    ->get();

    // Vérifier s'il y a une intersection entre les deux variables
    $intersect = array_intersect($articles, $article_0->pluck('id_shop_article')->toArray());

    if (count($intersect) == 1) {
        // S'il n'y a qu'une seule correspondance, retourner l'ID de l'article
        return array_values($intersect)[0];
    } else if (count($intersect) > 1) {
        // S'il y a plusieurs correspondances, retourner l'ID de l'article le plus cher
        $maxPrice = 0;
        $maxPriceArticle = 0;
        foreach($article_0 as $article) {
            if(in_array($article->id_shop_article, $intersect) && $article->totalprice > $maxPrice) {
                $maxPrice = $article->totalprice;
                $maxPriceArticle = $article->id_shop_article;
            }
        }
        return $maxPriceArticle;
    } else {
        // S'il n'y a pas de correspondance, retourner 0
        return 0;
    }
}

function MiseAuPanier($user_id, $id_article)
{
    $article = Shop_Article::findOrFail($id_article);
    $need_member = $article->need_member;

    // Vérifier si l'article nécessite une adhésion
    if ($need_member != 0) {
        // Vérifier si l'utilisateur est membre
        $is_member = isUserMember($user_id);
        if (isUserMember($user_id) == 0) {
            return $need_member ;
        } elseif (isUserMember($user_id) != $need_member) {
            //comparer les prix des deux articles si larticle.ttlprice est plus grand que celui de need_member on fait rien 
            //sinon on calcule la difference et on la met dans le panier
        }
    }

}


  




//fonction pour afficher la famille en fonction de l'id de la famille
function getUsersByFamilyId($family_id)
{
    $users = User::where('family_id', $family_id)->get();
    return $users;
}

//faire sortir la saison active en fonction de la table parametre
function saison_active()
{
    $saison = DB::table('parametre')
    ->where('activate', '=', 1)
    ->value('saison');
    return $saison;
}

//filtrer les articles en fonction de leur date de validité
function filterArticlesByValidityDate($articles) {
    $now = date('Y-m-d');

    $filteredArticles = collect($articles)->filter(function($article) use($now) {
        return ($article->startvalidity <= $now && $article->endvalidity >= $now);
    })->values();

    return $filteredArticles;
}

//filtrer les articles en fonction de selected_limit,Age & sexe
function getFilteredArticles($articles) {
    if (Auth::check()) {
        $user = Auth::user();
        $family = getUsersByFamilyId($user->family_id);
        $familyGender = $family->pluck('gender')->unique();
        $articleIds = collect($articles)->pluck('id_shop_article');
    
        $selectedArticles = DB::table('selected_limit')
                                ->whereIn('id_shop_article', $articleIds)
                                ->whereIn('user_id', $family->pluck('user_id'))
                                ->pluck('id_shop_article')
                                ->toArray();
    
        $filteredArticles = collect($articles)->filter(function($article) use ($selectedArticles, $user, $familyGender, $family) {
            if ($article->sex_limit && !$familyGender->contains($article->sex_limit)) {
                return false;
            }
    
            if ($article->selected_limit == 0) {
                return hasFamilyMemberWithAgeInRange($family, $article->agemin, $article->agemax) || in_array($article->id_shop_article, $selectedArticles);
                
            } else {
                return in_array($article->id_shop_article, $selectedArticles);
            }
        })->values();
    
    } else {
        $filteredArticles = collect($articles)->filter(function($article) {
            return ($article->selected_limit == 0 );
        })->values();
    }
    
    return $filteredArticles;
}

//filtrer les articles en fonction de l'age de la famille
function hasFamilyMemberWithAgeInRange($familyMembers, $agemin, $agemax) {
    foreach ($familyMembers as $member) {
        $age = Carbon::parse($member->birthdate)->age;
        if ($age >= $agemin && $age <= $agemax) {
            return true;
        }
    }
    return false;
}

//sortir les membres de la famille qui ont l'age requis
function getFamilyMembersMeetingAgeCriteria($family, $agemin, $agemax) {
    $members = collect($family)->filter(function($member) use ($agemin, $agemax) {
        $age = Carbon::parse($member->birthdate)->age;
        return ($age >= $agemin && $age <= $agemax);
    })->values();
    
    return $members;
}


// sortir les users qui peuvent acheter  l'article
function getArticleUsers($article) {
    $selectedArticles = [];
    $selectedUsers = [];

        $user = Auth::user();
        $family = getUsersByFamilyId($user->family_id);

        $selectedArticles = DB::table('selected_limit')
            ->where('id_shop_article', $article->id_shop_article)
            ->whereIn('user_id', $family->pluck('user_id'))
            ->pluck('id_shop_article')
            ->toArray();

        $selectedUsers = DB::table('selected_limit')
            ->where('id_shop_article', $article->id_shop_article)
            ->whereIn('user_id', $family->pluck('user_id'))
            ->pluck('user_id')
            ->toArray();

    $filteredUsers = collect($family)->filter(function($user) use ($selectedUsers, $article) {
        return in_array($user->user_id, $selectedUsers) && ($article->sex_limit == null || $user->gender == $article->sex_limit);
    })->values();

    $familyMembersMeetingAgeCriteria = getFamilyMembersMeetingAgeCriteria($family, $article->agemin, $article->agemax);
    $familyMembersMeetingAgeCriteria = $familyMembersMeetingAgeCriteria->filter(function($user) use ($article) {
        return ($article->sex_limit == null || $user->gender == $article->sex_limit);
    })->values();

    $filteredUsers = $filteredUsers->merge($familyMembersMeetingAgeCriteria);

    return $filteredUsers;


}

// recuperer l'ID de user et la saison et restituer les produits dont il est teacher en fonction de la saison
function retourner_shop_article_dun_teacher($user_id, $saison) {

    $selectedArticles = [];
    $selectedArticles_byuser = [];
    //recupere le role du user 
    $user_role = User::where("user_id",$user_id)->pluck('role')->first();
   
   
    if ($user_role >= 30 and $user_role<90) {

   
        $shop_article_saison = Shop_article_1::get();

        // recupere les shops article de type lesson
        $shop_article_lesson =   DB::table('shop_article')->where('saison',$saison)
       ->join('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')->get()->toArray();

       foreach($shop_article_lesson as $data1){

        $Data_teacher = json_decode($data1->teacher,true);

            foreach($Data_teacher as $data2){
                        if($user_id == $data2){
                            $selectedArticles_byuser[] = $data1->id_shop_article;
                        }
            }

       }

       return  $selectedArticles_byuser ;


        //recupere l'ensemble des teachers et l'id article(tableau with id)
       $shop_article_l =   DB::table('shop_article')->where('saison',$saison)
       ->join('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')->pluck('shop_article_1.id_shop_article','teacher')->toArray();
      

    }elseif($user_role>=90){
        $shop_article = shop_article::where('saison',$saison)->get()->toArray();
        $selectedArticles = $shop_article;
        return $selectedArticles ;
    }
        
    

}


// recuperer l'ID de user et la saison et restituer les shop articles achetes
function Donne_articles_Paye_d_un_user_aucours_d_une_saison($user_id, $saison) {

    
    $selectedArticles_buy = [];
  
    $shop_article_with_status = DB::table('bills')->where('status','>',60)->Where('id_user',$user_id)
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_shop_article')->unique()->toArray();

    $shop_article_saison_actuelle = DB::table('shop_article')->where('saison',$saison)->pluck('id_shop_article')->unique()->toArray() ;

    foreach ($shop_article_with_status as $value) {

       if(in_array($value,$shop_article_saison_actuelle)){
        $selectedArticles_buy[] = $value ;
       }


    }


    return $selectedArticles_buy;

}

// recuperer l'ID d'un shop articles et ramene tous les users qui l'ont achetees
function Donne_User_article_Paye($id_shop_article) {

    $shop_article_with_status = DB::table('bills')->where('status','>',60)->Where('id_shop_article',$id_shop_article)
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_user')->unique()->toArray();

    return   $shop_article_with_status;

}
// recuperer l'ID d'un shop articles et ramene tous les users 
function Donne_User_article($id_shop_article) {

    $shop_article_with_status = DB::table('bills')->Where('id_shop_article',$id_shop_article)
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_user')->unique()->toArray();

    return   $shop_article_with_status;

}


// recuperer l'ID d'un shop articles et retourne les USERS qui ont achete le produit a une date anterieure
function Donne_User_article_Date($id_shop_article,$date1) {

    $shop_article_with_date = DB::table('bills')->where('status','>',60)->Where('id_shop_article',$id_shop_article)->whereDate('date_bill', '<', $date1)
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_user')->unique()->toArray();

    return  $shop_article_with_date;
   
  
}


function Inscrits_Saison_Date($sasion, $date1){

}



function Inscrits_Saison_Final($saison){

}


// recuperer l'ID d'un shop article et ramene tous ceux qui ont achete le produit (buyers)

function retourner_buyers_dun_shop_article($id_shop_article) {

    $requete_liaison_shop_article_bills = LiaisonShopArticlesBill::where('id_shop_article',$id_shop_article)->pluck('id_user')->toArray();
    

    return $requete_liaison_shop_article_bills ;

}


// fonction pour determiner les destinataires des mails

function destinataires_du_mail($user_id){
    
    $tab = [] ;
    $tab1 = [] ;
   
    //recupere le famiily level pour verifier si l'user est un child ou un parent
    $requete_user_family_level = DB::table('users')->where('user_id',$user_id)->value('family_level');
  //recupere l'email du user
    $requete_user_email = DB::table('users')->where('user_id',$user_id)->value('email');

    if ($requete_user_family_level == "parent"){
        $tab[] = $user_id ;

        return $tab ;

    }else if ( ($requete_user_family_level == "child") and (( !isset($requete_user_email)) or ($requete_user_email == NULL) )){
        
        $requete_user_family_id = DB::table('users')->where('user_id',$user_id)->value('family_id');
        $tab = DB::table('users')->where('family_id', $requete_user_family_id) ->where('family_level','parent')->pluck('user_id')->toArray();

        foreach($tab as $t){
            if ($t != $user_id){
              $tab1[] = $t ;      
            }
        }
        return $tab1 ;



    }else if (($requete_user_family_level == "child") and (isset($requete_user_email)) ){

        $requete_user_family_id = DB::table('users')->where('user_id',$user_id)->value('family_id');
        $tab = DB::table('users')->where('family_id', $requete_user_family_id) ->where('family_level','parent')->pluck('user_id')->toArray();
      
        return $tab ;


    }
  

}





function sendEmailToUser($user_id, $message1,$data) {

  $user = User::findOrFail($user_id); // Find the user by ID or throw an exception
  $email = $user->email; // Get the user's email address

  Mail::to($email)->send(new UserEmail($message1,$data)); // Send the email using Laravel's Mail facade

}

class UserEmail extends \Illuminate\Mail\Mailable {
    
  public $message1; // Define a public property to store the message
  public $data;
 
  public function __construct($message1, $data) {
    $this->message1 = $message1; // Assign the message to the public property
    $this->data = $data ;
  }
  public function build() {
    return $this->subject('Gym Concordia [bureau]')->view('Communication/emailbody',['message1' => $this->message1, 'data' => $this->data]); // Define the email's view
  }

}















//fonctions pour afficher les dates en Anglais
function fetchDay($date){
    $lejour = ( new DateTime($date) )->format('l');

  $jour_semaine = array(
"lundi" => "Monday",
"Mardi" => "Tuesday",
"Mercredi" => "Wednesday",
"Jeudi" => "Thursday",
"Vendredi" => "Friday",
"Samedi" => "Saturday",
"Dimanche" => "Sunday"

  );

}




  
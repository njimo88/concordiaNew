<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\shop_article;
use App\Models\shop_article_1;
use App\Models\shop_article_2;
use App\Models\LiaisonShopArticlesBill;
use App\Models\Shop_category;
use App\Models\bills;
use App\Models\ShopMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Mail\SendMail;
use App\Models\ShopReduction;
use App\Models\LiaisonShopArticlesShopReductions;



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

// recuperer l'ID d'un shop article et ramene tous ceux qui ont achete le produit (buyers)

function retourner_buyers_dun_shop_article($id_shop_article) {

    $requete_liaison_shop_article_bills = LiaisonShopArticlesBill::where('id_shop_article',$id_shop_article)->pluck('id_user')->toArray();

    return $requete_liaison_shop_article_bills ;

}


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

    $article = Shop_article::findOrFail($id_article);
    $need_member = $article->need_member;

        $is_member = isUserMember($user_id);
        if ($is_member == 0) {
            return $need_member;
        } elseif ($is_member != $need_member) {

            // Récupérer les informations de l'article besoin membre
            $need_member_article = Shop_article::where('id_shop_article', $need_member)
                ->first();
            // Comparer les prix des deux articles
            if ($article->totalprice > $need_member_article->totalprice) {
                return 0;
            } else {
                // Calculer la différence entre le prix de l'article besoin membre et le prix de l'article ajouté au panier
                $diff_price = $need_member_article->totalprice - $article->totalprice;
                return $diff_price;
            }
        }
    

}

//fonction qui prend un $idArticle en paramètre et compte le nombre de fois qu'il est présent dans 
//le tableau renvoyé par Donne_articles_Paye_d_un_user_aucours_d_une_saison()

function countArticle($user_id, $idArticle)
{
    $saison = saison_active();
    // Récupérer tous les articles achetés par l'utilisateur au cours de la saison active
    $articles = Donne_articles_Paye_d_un_user_aucours_d_une_saison($user_id, $saison);

    // Initialiser le compteur à 0
    $count = 0;
    // Parcourir les articles pour compter le nombre de fois où l'idArticle apparaît
    foreach ($articles as $article) {
        if ($article == $idArticle) {
            $count++;
        }
    }

    // Retourner le nombre de fois que l'idArticle a été trouvé
    return $count;
}

 function MiseAjourStock()
{
   // Step 1: Retrieve the id_shop_article of the current season that have bills.status > 9
   $id_shop_articles = DB::table('shop_article')
   ->join('liaison_shop_articles_bills', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
   ->join('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
   ->where('shop_article.saison', '=', saison_active())
   ->where('bills.status', '>', 9)
   ->distinct('shop_article.id_shop_article')
   ->pluck('shop_article.id_shop_article');


   // Step 2: Count the occurrence of each id_shop_article multiplied by quantity in the liaison_shop_articles_bills table
    $liaison_counts = DB::table('liaison_shop_articles_bills')
    ->whereIn('id_shop_article', $id_shop_articles)
    ->select('id_shop_article', DB::raw('sum(quantity) as count'))
    ->groupBy('id_shop_article')
    ->pluck('count', 'id_shop_article');


    // Step 3: Update the stock_actuel for each article
    foreach ($id_shop_articles as $id_shop_article) {
        $shop_article = Shop_article::find($id_shop_article);
        if($shop_article->type_article == 2) {
            $article_2 = shop_article_2::find($shop_article->id_shop_article);
    
        $liaison_counts = DB::table('liaison_shop_articles_bills')
        ->join('shop_article_2', 'liaison_shop_articles_bills.id_shop_article', '=', 'shop_article_2.id_shop_article')
        ->where('liaison_shop_articles_bills.id_shop_article', $shop_article->id_shop_article)
        ->select(DB::raw('sum(quantity) as count, liaison_shop_articles_bills.declinaison'))
        ->groupBy('liaison_shop_articles_bills.declinaison')
        ->get();

        $stock_ini = 0;
        $stock_actuel = 0;
    
        $declinaisons = json_decode($article_2->declinaison, true);
        
        $new_declinaisons = [];
        foreach ($declinaisons as $key => $value) {
            foreach ($liaison_counts as $count) {
                if ($count->declinaison == $key+1) {
                    $value[$key+1]['stock_actuel_d'] = $count->count;
                }
            }
            $new_declinaisons[] = $value;
            $stock_ini += $value[$key+1]['stock_ini_d'];
            $stock_actuel += $value[$key+1]['stock_actuel_d'];
        }

        // Mettre à jour le tableau des déclinaisons dans la base de données
        $article_2->declinaison = json_encode($new_declinaisons);
        $article_2->save();
        
        // Mettre à jour les propriétés stock_ini et stock_actuel de l'article
        $shop_article->stock_ini = $stock_ini;
        $shop_article->stock_actuel = $stock_actuel;
        $shop_article->save();
        } else {
        $stock_ini = $shop_article->stock_ini;
        $count = $liaison_counts->get($id_shop_article, 0);
        $stock_actuel = $stock_ini - $count;
        $shop_article->stock_actuel = $stock_actuel;
        $shop_article->save();
         }

    }
}



function MiseAjourArticlePanier($articles){

    $liaison_counts = DB::table('liaison_shop_articles_bills')
        ->whereIn('id_shop_article', $articles->pluck('ref'))
        ->select('id_shop_article', DB::raw('sum(quantity) as count'))
        ->groupBy('id_shop_article')
        ->pluck('count', 'id_shop_article');

    foreach ($articles as $article) {
        $article_db = Shop_article::find($article->ref);
        if($article_db->type_article == 2) {
            $article_2 = shop_article_2::find($article_db->id_shop_article);
    
        $liaison_counts = DB::table('liaison_shop_articles_bills')
        ->join('shop_article_2', 'liaison_shop_articles_bills.id_shop_article', '=', 'shop_article_2.id_shop_article')
        ->where('liaison_shop_articles_bills.id_shop_article', $article_db->id_shop_article)
        ->select(DB::raw('sum(quantity) as count, liaison_shop_articles_bills.declinaison'))
        ->groupBy('liaison_shop_articles_bills.declinaison')
        ->get();

        $stock_ini = 0;
        $stock_actuel = 0;
    
        $declinaisons = json_decode($article_2->declinaison, true);
        
        $new_declinaisons = [];
        foreach ($declinaisons as $key => $value) {
            foreach ($liaison_counts as $count) {
                if ($count->declinaison == $key+1) {
                    $value[$key+1]['stock_actuel_d'] = $count->count;
                }
            }
            $new_declinaisons[] = $value;
            $stock_ini += $value[$key+1]['stock_ini_d'];
            $stock_actuel += $value[$key+1]['stock_actuel_d'];
        }

        // Mettre à jour le tableau des déclinaisons dans la base de données
        $article_2->declinaison = json_encode($new_declinaisons);
        $article_2->save();
        
        // Mettre à jour les propriétés stock_ini et stock_actuel de l'article
        $article_db->stock_ini = $stock_ini;
        $article_db->stock_actuel = $stock_actuel;
        $article_db->save();
        }else{
            $stock_ini = $article_db->stock_ini;
        $count = $liaison_counts->get($article->ref, 0);
        $stock_actuel = $stock_ini - $count;
        $article_db->stock_actuel = $stock_actuel;
        $article_db->save();
        }
        
    }
}


function MiseAjourArticle($article){
    if ($article->type_article == 2) {
        $article_2 = shop_article_2::find($article->id_shop_article);
    
        $liaison_counts = DB::table('liaison_shop_articles_bills')
        ->join('shop_article_2', 'liaison_shop_articles_bills.id_shop_article', '=', 'shop_article_2.id_shop_article')
        ->where('liaison_shop_articles_bills.id_shop_article', $article->id_shop_article)
        ->select(DB::raw('sum(quantity) as count, liaison_shop_articles_bills.declinaison'))
        ->groupBy('liaison_shop_articles_bills.declinaison')
        ->get();

        $stock_ini = 0;
        $stock_actuel = 0;
    
        $declinaisons = json_decode($article_2->declinaison, true);
        
        $new_declinaisons = [];
        foreach ($declinaisons as $key => $value) {
            foreach ($liaison_counts as $count) {
                if ($count->declinaison == $key+1) {
                    $value[$key+1]['stock_actuel_d'] = $count->count;
                }
            }
            $new_declinaisons[] = $value;
            $stock_ini += $value[$key+1]['stock_ini_d'];
            $stock_actuel += $value[$key+1]['stock_actuel_d'];
        }

        // Mettre à jour le tableau des déclinaisons dans la base de données
        $article_2->declinaison = json_encode($new_declinaisons);
        $article_2->save();
        
        // Mettre à jour les propriétés stock_ini et stock_actuel de l'article
        $article->stock_ini = $stock_ini;
        $article->stock_actuel = $stock_actuel;
        $article->save();
        
    }
    
    
    else{
        $liaison_counts = DB::table('liaison_shop_articles_bills')
        ->where('id_shop_article', $article->id_shop_article)
        ->select(DB::raw('sum(quantity) as count'))
        ->value('count');

        $stock_ini = $article->stock_ini;
        $count = $liaison_counts ?? 0;
        $stock_actuel = $stock_ini - $count;
        $article->stock_actuel = $stock_actuel;
        $article->save();
    }
    
}


function verifierStockUnArticle($article, $quantite){
    $stockActuel = $article->stock_actuel;
    if($quantite <= $stockActuel){
        return true;
    } else {
        return false;
    }
}

function verifierStockUnArticlePanier($articles,$quantite){
    $stockActuel = $articles->stock_actuel;
    if($quantite <= $stockActuel){
        return true;
    } else {
        return false;
    }
}

function calculerPaiements(float $total, int $nbfois) {
    $paiements = [];
    $montant = 0.8 * $total / $nbfois;
    $premierMontant = $montant + 0.2 * $total;
    $paiements[] = round($premierMontant,2);
    $var = 0;
    for ($i = 0; $i < $nbfois - 2; $i++) {
        $var += $montant;
        $paiements[] = round($montant,2);
    }

    $dernierMontant =$total - round ( $var + $premierMontant,2);
    $paiements[] = $dernierMontant;
    return $paiements;
}




function getUsersBirthdayToday()
{
    $saison = saison_active();

    // Détermination de la date d'anniversaire d'aujourd'hui
    $today = Carbon::now();
    $birthday = $today->format('m-d');

    // Récupération des utilisateurs qui ont acheté un article de type 0 cette saison ou la saison précédente
    $users = DB::table('users')
        ->join('liaison_shop_articles_bills', 'users.user_id', '=', 'liaison_shop_articles_bills.id_user')
        ->join('shop_article', 'liaison_shop_articles_bills.id_shop_article', '=', 'shop_article.id_shop_article')
        ->whereIn('shop_article.saison', [$saison, $saison-1]) // saison courante ou précédente
        ->where('shop_article.type_article', '=', 0) // Type article 0 = article de saison
        ->select('users.*')
        ->distinct()
        ->get();

    // Filtrage des utilisateurs dont c'est l'anniversaire aujourd'hui
   // Filtrage des utilisateurs dont c'est l'anniversaire aujourd'hui et qui ont une date de naissance définie
$usersWithBirthday = $users->filter(function($user) use ($birthday) {
    if ($user->birthdate !== null) {
        $userBirthday = Carbon::parse($user->birthdate)->format('m-d');
             
        return $userBirthday == $birthday;
    }
    return false;
});

    return $usersWithBirthday;
}


use Intervention\Image\ImageManagerStatic as Image;


function printUsersBirthdayOnImage()
{
    $users = getUsersBirthdayToday();

    $image = Image::make(public_path('assets/images/birthday.jpg'));
    
    setlocale(LC_TIME, 'fr_FR.utf8');

    // Ajout du texte souhaité au centre de l'image
    $daysOfWeek = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
    $months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

    $currentDayOfWeek = $daysOfWeek[strftime('%u')%7];
    $currentMonth = $months[strftime('%m')-1%12];

    $message = "En ce " . $currentDayOfWeek . " " . strftime("%e") . " " . $currentMonth . " " . strftime("%Y") . ", nous souhaitons l'anniversaire à:";
    $image->text($message, $image->width() / 4.6, 130, function($font) {
        $font->file(public_path('fonts/Pacifico-Regular.ttf'));
        $font->size(15);
        $font->color('#000000');
        $font->align('left');
        $font->valign('top');
    });



    $y = 165;
    $line_count = 0;
    foreach ($users as $index => $user) {
        $age = Carbon::parse($user->birthdate)->diffInYears(Carbon::now());
        $text = $user->name . ' ' . $user->lastname . ' (' . $age . ' ans)';
        $x = $line_count % 2 == 0 ? $image->width() / 2 : $image->width() / 5;
        $image->text($text, $x, $y, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(10);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });
        $line_count++;
        if ($line_count % 2 == 0) {
            $y += 16;
        }
    }
    



    // Sauvegarde de l'image modifiée
    $date = new DateTime();
    $dateString = $date->format('Y-m-d');
    $filename = $dateString . "-birthday.jpg";
    $image->save(public_path('assets/images/' . $filename));

}


function updateTotalCharges($bill_id) {
    $messages = ShopMessage::where('id_bill', $bill_id)->get(); 
    $total_payed = 0; 
    foreach ($messages as $message) {
        $total_payed += $message->somme_payé; 
    }
    $bill = bills::find($bill_id); 
    $bill->amount_paid = $total_payed; 
    $bill->save(); 
    return $total_payed;
}


// recuperer l'ID d'un shop articles et retourne les USERS qui ont achete le produit a une date anterieure
function Donne_User_article_Date($id_shop_article,$date1) {

    $shop_article_with_date = DB::table('bills')->where('status','>',60)->Where('id_shop_article',$id_shop_article)->whereDate('date_bill', '<', $date1)
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_user')->unique()->toArray();

    return  $shop_article_with_date;
   
  
}


function Donne_User_article_Paye($id_shop_article) {

    $shop_article = DB::table('bills')->where('status','>',60)->Where('id_shop_article',$id_shop_article)
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_user')->unique()->toArray();

    return  $shop_article;
   
  
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





/* -------------------------SendEmailToUser using the id ------------------------------- 
function sendEmailToUser($user_id, $message1,$email_sender,$userName) {

  $user = User::findOrFail($user_id); // Find the user by ID or throw an exception
  $email = $user->email; // Get the user's email address

    // Set the SMTP credentials dynamically
    $config = [
        'driver' => "smtp",
        'host' => "smtp.ionos.fr",
        'port' => 465,
        'from' => ['address' => $email_sender, 'name' => $userName],
        'encryption' => "ssl",
        'username' => "webmaster@gym-concordia.com",
        'password' => "mickmickmath&67_mickmickmath&67"
    ];

    Mail::mailer('smtp')->to($email)->cc($email_sender)->bcc($email_sender)->send(new ContactFormMail($email_sender, $message1,$userName));
   
 
}

*/


/*

class UserEmail extends \Illuminate\Mail\Mailable {
    
  public $email_sender;  
  public $message1; // Define a public property to store the message
  public $userName;


  
 
  public function __construct($email_sender,$message1, $userName) {
    $this->message1     = $message1; // Assign the message to the public property
    $this->email_sender = $email_sender ;
    $this->userName     = $userName;
    
  }
  public function build() {
    return $this->subject('Gym Concordia [bureau]')->view('Communication/emailbody',['message1' => $this->message1]); // Define the email's view

    return  $this->from($this->email_sender, $this->userName)
    ->subject('['.$this->userName.'] Message d\'un utilisateur')
    ->view('Communication/emailbody')
    ->with([
        'message' => $this->message1,
    ]);

    
  }

}


*/

/* -------------------------SendEmail ------------------------------- */

function receiveEmailFromUser(Request $request,$email_destinataire) {
    $email = $request->input('email');
    $message = $request->input('message');
    $nom = $request->input('name');

    
    Mail::raw($message, function($message) use ($email,$email_destinataire,$nom) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                ->to($email_destinataire)
                ->subject('['.$nom.'] Message d\'un utilisateur')
                ->replyTo($email);
    });

    

    //dd($email);

    
}

/* -------------------------another Email function  ------------------------------- */



function envoiEmail($userEmail, $message,$receiverEmail,$userName) {

   
    // Set the SMTP credentials dynamically
$config = [
    'driver' => "smtp",
    'host' => "smtp.ionos.fr",
    'port' => 465,
    'from' => ['address' => $userEmail, 'name' => $userName],
    'encryption' => "ssl",
    'username' => "webmaster@gym-concordia.com",
    'password' => "mickmickmath&67_mickmickmath&67"
];

    

    Mail::mailer('smtp')->to($receiverEmail)->send(new ContactFormMail($userEmail, $message,$userName));
}


class ContactFormMail  extends \Illuminate\Mail\Mailable{
 

    public $userEmail;
    public $message;
    public $userName;
  

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userEmail, $message, $userName)
    {
        $this->userEmail = $userEmail;
        $this->message = $message;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->from($this->userEmail, $this->userName)
                    ->subject('['.$this->userName.'] Message d\'un utilisateur')
                    ->view('Communication/form_email')
                    ->with([
                        'content_Email' => $this->message,
                    ]);
    }


    }



    
    function envoiEmail2($userEmail, $message,$receiverEmail,$userName,$titre) {

    
        // Set the SMTP credentials dynamically
    $config = [
        'driver' => "smtp",
        'host' => "smtp.ionos.fr",
        'port' => 465,
        'from' => ['address' => $userEmail, 'name' => $userName],
        'encryption' => "ssl",
        'username' => "webmaster@gym-concordia.com",
        'password' => "mickmickmath&67_mickmickmath&67"
    ];

        

        Mail::mailer('smtp')->to($receiverEmail)->send(new ContactFormMail_module_com($userEmail, $message,$userName,$titre));
    }



    class ContactFormMail_module_com  extends \Illuminate\Mail\Mailable{
 

        public $userEmail;
        public $message;
        public $userName;
        public $titre;
    
        /**
         * Create a new message instance.
         *
         * @return void
         */
        public function __construct($userEmail, $message, $userName,$titre)
        {
            $this->userEmail = $userEmail;
            $this->message = $message;
            $this->userName = $userName;
            $this->titre    = $titre ;
        }
    
        /**
         * Build the message.
         *
         * @return $this
         */
        public function build()
        {
            return  $this->from($this->userEmail, $this->userName)
                        ->subject('['.$this->userName.'] Message d\'un utilisateur')
                        ->view('Communication/emailbody')
                        ->with([
                            'message1' => $this->message,
                            'the_title' => $this->titre
                        ]);
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


function getReducedPrice($articleId, $originalPrice) {
    $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();

    $reducedPrice = $originalPrice;
    
    foreach($shopReductions as $shopReduction) {
        $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
            ->whereNull('destroy')
            ->where('state', 1)
            ->whereDate('startvalidity', '<=', now())
            ->whereDate('endvalidity', '>=', now())
            ->first();
        if($reduction) {
            if($reduction->value) {
                $reducedPrice -= $reduction->value;
            } elseif($reduction->percentage) {
                $reducedPrice *= (1 - ($reduction->percentage / 100));
            }
        }
    }


    return $reducedPrice;
}
            

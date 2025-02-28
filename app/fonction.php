<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Shop_article;
use App\Models\shop_article_1;
use App\Models\shop_article_2;
use App\Models\LiaisonShopArticlesBill;
use App\Models\Shop_category;
use App\Models\member_history;
use App\Models\bills;
use App\Models\old_bills;
use App\Models\ShopMessage;
use App\Models\UserReductionUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManagerStatic as Image;
use App\Mail\SendMail;
use App\Models\ShopReduction;
use App\Models\LiaisonShopArticlesShopReductions;
use App\Models\LiaisonUserShopReduction;
use App\Models\Basket;
use App\Http\Controllers\generatePDF;
use App\Models\SystemSetting;
use App\Models\Parametre;

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


function hasExistingReduction($userId, $pourUserId, $shopArticleId)
{

    // ID de la réduction spécifique à vérifier
    $specificReductionId = 7;

    // Vérifier si un article dans le panier de l'utilisateur est lié à la réduction spécifique

    $baskets = Basket::where('user_id', $userId)
        ->where('pour_user_id', $pourUserId)
        ->where('ref', '!=', $shopArticleId)
        ->get();
    foreach ($baskets as $basket) {
        $hasReduction = LiaisonShopArticlesShopReductions::where('id_shop_article', $basket->ref)
            ->where('id_shop_reduction', $specificReductionId)
            ->exists();

        if ($hasReduction) {
            return true;
        }
    }

    return false;
}


//filtrer les articles en fonction de leur date de validité
function filterArticlesByValidityDate($articles)
{
    $now = date('Y-m-d');

    $filteredArticles = collect($articles)->filter(function ($article) use ($now) {
        return ($article->startvalidity <= $now && $article->endvalidity >= $now);
    })->values();

    return $filteredArticles;
}

//filtrer les articles en fonction de selected_limit,Age & sexe
function getFilteredArticles($articles)
{
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

        $filteredArticles = collect($articles)->filter(function ($article) use ($selectedArticles, $user, $familyGender, $family) {
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
        $filteredArticles = collect($articles)->filter(function ($article) {
            return ($article->selected_limit == 0);
        })->values();
    }
    return $filteredArticles;
}

//filtrer les articles en fonction de l'age de la famille
function hasFamilyMemberWithAgeInRange($familyMembers, $agemin, $agemax)
{
    foreach ($familyMembers as $member) {
        $birthdate = Carbon::parse($member->birthdate);
        $now = Carbon::now();
        $daysSinceBirth = $now->diffInDays($birthdate);
        $age = $daysSinceBirth / 365.25;
        if ($age >= $agemin && $age <= $agemax) {
            return true;
        }
    }
    return false;
}


//sortir les membres de la famille qui ont l'age requis
function getFamilyMembersWithAgeInRange($familyMembers, $agemin, $agemax)
{
    $filteredMembers = [];

    foreach ($familyMembers as $member) {
        $birthdate = Carbon::parse($member->birthdate);
        $now = Carbon::now();
        $daysSinceBirth = $now->diffInDays($birthdate);
        $age = $daysSinceBirth / 365.25;

        if ($age >= $agemin && $age <= $agemax) {
            $filteredMembers[] = $member;
        }
    }

    return $filteredMembers;
}



// sortir les users qui peuvent acheter  l'article
function getArticleUsers($article)
{
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

    $filteredUsers = collect($family)->filter(function ($user) use ($selectedUsers, $article) {
        return in_array($user->user_id, $selectedUsers) && ($article->sex_limit == null || $article->sex_limit == 0 || $user->gender == $article->sex_limit);
    })->values();

    $familyMembersMeetingAgeCriteria = getFamilyMembersWithAgeInRange($family, $article->agemin, $article->agemax);
    $familyMembersMeetingAgeCriteria = collect($familyMembersMeetingAgeCriteria);
    $familyMembersMeetingAgeCriteria = $familyMembersMeetingAgeCriteria->filter(function ($user) use ($article) {
        return ($article->sex_limit == null || $user->gender == $article->sex_limit);
    })->values();

    $filteredUsers = $filteredUsers->merge($familyMembersMeetingAgeCriteria);
    return $filteredUsers;
}

// recuperer l'ID de user et la saison et restituer les produits dont il est teacher en fonction de la saison
function retourner_shop_article_dun_teacher($user_id, $saison)
{

    $selectedArticles = [];
    $selectedArticles_byuser = [];
    //recupere le role du user 
    $user_role = User::where("user_id", $user_id)->pluck('role')->first();


    if ($user_role >= 30 and $user_role < 90) {


        $shop_article_saison = Shop_article_1::get();

        // recupere les shops article de type lesson
        $shop_article_lesson =   DB::table('shop_article')->where('saison', $saison)
            ->join('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')->get()->toArray();

        foreach ($shop_article_lesson as $data1) {

            $Data_teacher = json_decode($data1->teacher, true);

            foreach ($Data_teacher as $data2) {
                if ($user_id == $data2) {
                    $selectedArticles_byuser[] = $data1->id_shop_article;
                }
            }
        }

        return  $selectedArticles_byuser;


        //recupere l'ensemble des teachers et l'id article(tableau with id)
        $shop_article_l =   DB::table('shop_article')->where('saison', $saison)
            ->join('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')->pluck('shop_article_1.id_shop_article', 'teacher')->toArray();
    } elseif ($user_role >= 90) {
        $shop_article = Shop_article::where('saison', $saison)->get()->toArray();
        $selectedArticles = $shop_article;
        return $selectedArticles;
    }
}

// recuperer l'ID d'un shop article et ramene tous ceux qui ont achete le produit (buyers)







//fonctions pour afficher les dates


// recuperer l'ID d'un shop article et ramene tous ceux qui ont achete le produit (buyers)

function retourner_buyers_dun_shop_article($id_shop_article)
{

    $requete_liaison_shop_article_bills = LiaisonShopArticlesBill::join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
        ->where('id_shop_article', $id_shop_article)
        ->where('bills.status', '>', 9)
        ->pluck('liaison_shop_articles_bills.id_user')
        ->toArray();

    return $requete_liaison_shop_article_bills;
}


function findFamilyIdByUserId($id_user)
{
    return DB::table('users')->where('user_id', $id_user)->value('family_id');
}


//fonctions pour afficher les dates
function fetchDayy($date)
{
    $lejour = (new DateTime($date))->format('l');
    $mois = (new DateTime($date))->format('F');

    $jour_semaine = array(
        "lundi" => "Monday",
        "mardi" => "Tuesday",
        "mercredi" => "Wednesday",
        "jeudi" => "Thursday",
        "vendredi" => "Friday",
        "samedi" => "Saturday",
        "dimanche" => "Sunday"
    );

    $mois_fr = array(
        "janvier" => "January",
        "février" => "February",
        "mars" => "March",
        "avril" => "April",
        "mai" => "May",
        "juin" => "June",
        "juillet" => "July",
        "août" => "August",
        "septembre" => "September",
        "octobre" => "October",
        "novembre" => "November",
        "décembre" => "December",
    );

    $jour_semaine_fr = array_flip($jour_semaine);
    $mois_fr = array_flip($mois_fr);

    if (isset($jour_semaine_fr[$lejour]) && isset($mois_fr[$mois])) {
        return ucfirst($jour_semaine_fr[$lejour]) . ' ' . $mois_fr[$mois];
    }

    return '';
}

function fetchDayy2($date)
{
    $lejour = (new DateTime($date))->format('l');

    $jour_semaine = array(
        "lundi" => "Monday",
        "mardi" => "Tuesday",
        "mercredi" => "Wednesday",
        "jeudi" => "Thursday",
        "vendredi" => "Friday",
        "samedi" => "Saturday",
        "dimanche" => "Sunday"
    );

    $jour_semaine_fr = array_flip($jour_semaine);

    if (isset($jour_semaine_fr[$lejour])) {
        return ucfirst($jour_semaine_fr[$lejour]);
    }

    return '';
}
function fetcchDayy($date)
{
    $lejour = (new DateTime($date))->format('l');

    $jour_semaine = array(
        "lundi" => "Monday",
        "mardi" => "Tuesday",
        "mercredi" => "Wednesday",
        "jeudi" => "Thursday",
        "vendredi" => "Friday",
        "samedi" => "Saturday",
        "dimanche" => "Sunday"
    );

    $jour_semaine_fr = array_flip($jour_semaine);

    if (isset($jour_semaine_fr[$lejour])) {
        return ucfirst($jour_semaine_fr[$lejour]);
    }

    return '';
}

function fetchMonthh($date)
{
    $mois = (new DateTime($date))->format('F');

    $mois_fr = array(
        "janvier" => "January",
        "février" => "February",
        "mars" => "March",
        "avril" => "April",
        "mai" => "May",
        "juin" => "June",
        "juillet" => "July",
        "août" => "August",
        "septembre" => "September",
        "octobre" => "October",
        "novembre" => "November",
        "décembre" => "December",
    );

    $mois_fr = array_flip($mois_fr);

    if (isset($mois_fr[$mois])) {
        return ucfirst($mois_fr[$mois]);
    }

    return '';
}


// recuperer l'ID de user et la saison et restituer les shop articles achetes
function Donne_articles_Paye_d_un_user_aucours_d_une_saison($user_id, $saison)
{


    $selectedArticles_buy = [];

    $shop_article_with_status = DB::table('bills')->where('status', '>', 60)->Where('id_user', $user_id)
        ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_shop_article')->unique()->toArray();

    $shop_article_saison_actuelle = DB::table('shop_article')->where('saison', $saison)->pluck('id_shop_article')->unique()->toArray();

    foreach ($shop_article_with_status as $value) {

        if (in_array($value, $shop_article_saison_actuelle)) {
            $selectedArticles_buy[] = $value;
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
        foreach ($article_0 as $article) {
            if (in_array($article->id_shop_article, $intersect) && $article->totalprice > $maxPrice) {
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
        if (getReducedPrice($id_article, $article->totalprice, $user_id) > getReducedPrice($id_article, $need_member_article->totalprice, $user_id)) {
            return 0;
        } else {
            // Calculer la différence entre le prix de l'article besoin membre et le prix de l'article ajouté au panier
            $diff_price = getReducedPrice($id_article, $need_member_article->totalprice, $user_id) - getReducedPrice($id_article, $article->totalprice, $user_id);
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
    $articles = LiaisonShopArticlesBill::where('liaison_shop_articles_bills.id_user', $user_id)
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('shop_article.saison', $saison)
        ->get()
        ->pluck('id_shop_article')
        ->toArray();

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


function canAddMoreOfArticle($selected_user_id, Shop_article $article)
{
    $userId = $selected_user_id;
    $basketItems = Basket::where('pour_user_id', $userId)
        ->where('ref', $article->id_shop_article)
        ->get();

    $totalQuantity = $basketItems->sum('qte');
    if ($totalQuantity >= $article->max_per_user) {
        return false;
    }

    return true;
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
        ->join('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
        ->whereIn('liaison_shop_articles_bills.id_shop_article', $id_shop_articles)
        ->where('bills.status', '>', 9)
        ->select('liaison_shop_articles_bills.id_shop_article', DB::raw('sum(liaison_shop_articles_bills.quantity) as count'))
        ->groupBy('liaison_shop_articles_bills.id_shop_article')
        ->pluck('count', 'liaison_shop_articles_bills.id_shop_article');

    // Step 3: Update the stock_actuel for each article
    foreach ($id_shop_articles as $id_shop_article) {
        $shop_article = Shop_article::find($id_shop_article);

        $stock_ini = $shop_article->stock_ini;
        $count = $liaison_counts->get($id_shop_article, 0);

        if ($shop_article->type_article == 2 && $shop_article->declinaisons->isNotEmpty()) {
            // For type 2 articles with declinaisons, handle their declinaisons
            $declinaisons = $shop_article->declinaisons;
            $totalStock = 0;

            foreach ($declinaisons as $declinaison) {
                $soldCount = LiaisonShopArticlesBill::where('id_shop_article', $shop_article->id_shop_article)
                    ->where('declinaison', $declinaison->id)
                    ->sum('quantity');

                $declinaisonStock = $declinaison->stock_ini_d - $soldCount; // Subtract the sold quantity from stock_ini_d to get the current stock for the declinaison
                $declinaison->stock_actuel_d = $declinaisonStock;
                $declinaison->save();

                $totalStock += $declinaisonStock;
            }

            // Update the article's stock_actuel with the sum of all its declinaisons' stock_actuel_d
            $shop_article->stock_actuel = $totalStock;
        } else {
            // For other articles or type 2 articles without declinaisons, just decrease the stock_actuel based on the liaison_counts
            $shop_article->stock_actuel = $stock_ini - $count;
        }

        $shop_article->save();
    }
}




function MiseAjourArticlePanier($articles)
{

    $liaison_counts = DB::table('liaison_shop_articles_bills')
        ->join('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
        ->whereIn('liaison_shop_articles_bills.id_shop_article', $articles->pluck('ref'))
        ->where('bills.status', '>', 9)
        ->select('liaison_shop_articles_bills.id_shop_article', DB::raw('sum(liaison_shop_articles_bills.quantity) as count'))
        ->groupBy('liaison_shop_articles_bills.id_shop_article')
        ->pluck('count', 'liaison_shop_articles_bills.id_shop_article');

    foreach ($articles as $article) {
        $article_db = Shop_article::find($article->ref);

        if ($article_db->type_article == 2 && $article_db->declinaisons->isNotEmpty()) {
            $totalStock = 0;

            foreach ($article_db->declinaisons as $declinaison) {
                $soldCount = LiaisonShopArticlesBill::where('id_shop_article', $article_db->id_shop_article)
                    ->where('declinaison', $declinaison->id)
                    ->sum('quantity');

                $declinaisonStock = $declinaison->stock_ini_d - $soldCount;
                $declinaison->stock_actuel_d = $declinaisonStock;
                $declinaison->save();

                $totalStock += $declinaisonStock;
            }

            $article_db->stock_actuel = $totalStock;
        } else {
            $stock_ini = $article_db->stock_ini;
            $count = $liaison_counts->get($article->ref, 0);
            $stock_actuel = $stock_ini - $count;
            $article_db->stock_actuel = $stock_actuel;
        }

        $article_db->save();
    }
}



function MiseAjourArticle($article)
{

    $liaison_counts = DB::table('liaison_shop_articles_bills')
        ->join('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
        ->where('liaison_shop_articles_bills.id_shop_article', $article->id_shop_article)
        ->where('bills.status', '>', 9)
        ->select(DB::raw('sum(liaison_shop_articles_bills.quantity) as count'))
        ->value('count');

    if ($article->type_article == 2 && $article->declinaisons->isNotEmpty()) {
        $totalStock = 0;

        foreach ($article->declinaisons as $declinaison) {
            $soldCount = LiaisonShopArticlesBill::where('id_shop_article', $article->id_shop_article)
                ->where('declinaison', $declinaison->id)
                ->sum('quantity');

            $declinaisonStock = $declinaison->stock_ini_d - $soldCount;
            $declinaison->stock_actuel_d = $declinaisonStock;
            $declinaison->save();

            $totalStock += $declinaisonStock;
        }

        $article->stock_actuel = $totalStock;
    } else {
        $stock_ini = $article->stock_ini;
        $count = $liaison_counts ?? 0;
        $stock_actuel = $stock_ini - $count;
        $article->stock_actuel = $stock_actuel;
    }

    $article->save();
}



function verifierStockUnArticle($article, $quantite)
{
    $stockActuel = $article->stock_actuel;
    if ($quantite <= $stockActuel) {
        return true;
    } else {
        return false;
    }
}

function verifierStockUnArticlePanier($articles, $quantite)
{
    $stockActuel = $articles->stock_actuel;
    if ($quantite <= $stockActuel) {
        return true;
    } else {
        return false;
    }
}

function calculerPaiements(int $methodePaiement, float $total, int $nbfois)
{
    $paiements = [];


    $montant = 0.8 * $total / $nbfois;
    $premierMontant = $montant + 0.2 * $total;
    $paiements[] = round($premierMontant, 2);
    $var = 0;
    for ($i = 0; $i < $nbfois - 2; $i++) {
        $var += $montant;
        $paiements[] = round($montant, 2);
    }

    $dernierMontant = $total - round($var + $premierMontant, 2);
    if ($dernierMontant > 0) {
        $paiements[] = $dernierMontant;
    }
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
        ->whereIn('shop_article.saison', [$saison, $saison - 1]) // saison courante ou précédente
        ->where('shop_article.type_article', '=', 0) // Type article 0 = article de saison
        ->select('users.*')->orderBy('users.birthdate', 'desc')
        ->orderBy('users.name')
        ->orderBy('users.lastname')
        ->distinct()
        ->get();

    // Filtrage des utilisateurs dont c'est l'anniversaire aujourd'hui et qui ont une date de naissance définie
    $usersWithBirthday = $users->filter(function ($user) use ($birthday) {
        if ($user->birthdate !== null) {
            $userBirthday = Carbon::parse($user->birthdate)->format('m-d');

            return $userBirthday == $birthday;
        }
        return false;
    });

    return $usersWithBirthday;
}


function printUsersBirthdayOnImage()
{
    $users = getUsersBirthdayToday();
    $image = Image::make(public_path('assets/images/birthdays-template.png'));

    // Définition des jours et mois en français
    $daysOfWeek = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    $months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    // Récupération de la date actuelle
    $now = Carbon::today();
    $currentDayOfWeek = $daysOfWeek[$now->dayOfWeek];
    $currentMonth = $months[$now->month - 1];

    // Construction du message avec la date correcte
    $message = mb_strtoupper($currentDayOfWeek) . " " . $now->day . " " . mb_strtoupper($currentMonth) . " " . $now->year;

    // Message d'anniversaire
    $annivMessage = "Nous souhaitons un joyeux anniversaire à :";

    $startX = 50;
    $endX = 2350;

    // Calcul de la largeur du message
    $bbox = imagettfbbox(53, 0, public_path('fonts/Grilcbto.ttf'), $message);
    $messageWidth = abs($bbox[4] - $bbox[0]);

    // Position centrée du texte
    $textX = ($startX + $endX - $messageWidth) / 2;

    // Ajout du message sur l'image
    $image->text($message, $textX, 155, function ($font) {
        $font->file(public_path('fonts/Grilcbto.ttf'));
        $font->size(53);
        $font->color('#000000');
        $font->align('left');
        $font->valign('center');
    });

    $startX = 115;

    // Calcul de la largeur du message d'anniversaire
    $annivBbox = imagettfbbox(40, 0, public_path('fonts/Grilcbto.ttf'), $annivMessage);
    $annivMessageWidth = abs($annivBbox[4] - $annivBbox[0]);

    // Position centrée du texte d'anniversaire
    $annivTextX = ($startX + $endX - $annivMessageWidth) / 2;

    // Ajout du texte d'anniversaire sur l'image
    $image->text($annivMessage, $annivTextX, 250, function ($font) {
        $font->file(public_path('fonts/Grilcbto.ttf'));
        $font->size(40);
        $font->color('#000000');
        $font->align('left');
        $font->valign('top');
    });

    // Initialisation des variables
    $globalY = 400; // Y global
    $y = $globalY; // Position Y de départ

    // Largeur totale de l'image
    $imageWidth = $image->width();

    // Largeur d'une colonne (diviser l'image en 4.5 parties pour gérer plusieurs colonnes)
    $columnWidth = $imageWidth / 3;

    // Position X de départ (première colonne à gauche)
    $startX = 365;

    // Début du texte dans la première colonne
    $x = $startX;

    $etc = False;
    $maxLinesPerColumn = 5;
    $maxColumns = 2;
    $counter = 0;

    foreach ($users as $index => $user) {
        $firstname = mb_strtoupper($user->name);
        $lastname = ucfirst(mb_strtolower($user->lastname));

        // Calcul de l'âge
        $age = Carbon::parse($user->birthdate)->diffInYears(Carbon::now());

        // Concaténation pour bon texte
        $text = $lastname . ' ' . $firstname;
        $text = trim($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = mb_substr($text, 0, 21);
        $text = $text . ' (' . $age . ' ans)';

        if ($counter % 2 == 0) {
            $x = $startX;

            if ($counter !== 0) {
                $y += 60;
            }
        } else {
            $x = $startX + ($columnWidth * 1.15);
        }

        // Vérifier si on a atteint le nombre max de membres
        if ($counter >= ($maxColumns * $maxLinesPerColumn)) {
            $etc = True;
            break;
        }

        // Ajouter le texte à l'image
        $image->text($text, $x, $y, function ($font) {
            $font->file(public_path('fonts/Grilcbto.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->valign('baseline');
        });

        $counter++;
    }


    $image->text("Voir plus +", 1950, 680, function ($font) {
        $font->file(public_path('fonts/Grilcbto.ttf'));
        $font->size(45);
        $font->color('#FF0000');
        $font->align('left');
        $font->valign('top');
    });

    if ($etc) {
        $image->text("Etc...", $startX + ($columnWidth * 1.15), 680, function ($font) {
            $font->file(public_path('fonts/Grilcbto.ttf'));
            $font->size(45);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });
    }

    // Sauvegarde de la nouvelle image
    $filename = "birthdays.jpg";
    $image->encode('png', 100);
    $image->save(public_path('uploads/Slider/' . $filename));

    chmod(public_path('uploads/Slider/' . $filename), 0755);

    sendBirthdayMail($users); // Appel de la fonction pour envoyer un message aux personnes qui ont anniversaire
}


function sendBirthdayMail($usersBirthday)
{
    foreach ($usersBirthday as $user) {
        $recipientMail = $user->email;
        if ($recipientMail == null || $recipientMail == "") {
            $receiverMails = User::where('family_id', '=', $user->family_id)->where('family_level', '=', 'parent')->where('email', '!=', null)->pluck('email')->toArray();
        } else {
            $receiverMails = [$recipientMail];
        }

        $senderEmail = 'anniversaire@gym-concordia.com';
        $senderName = 'Gym Concordia';
        $recipientName = $user->lastname . ' ' . $user->name;

        foreach ($receiverMails as $receiverMail) {
            Mail::send([], [], function ($message) use ($recipientName, $receiverMail, $senderEmail, $senderName) {
                $message->from($senderEmail, $senderName)
                    ->to($receiverMail)
                    ->subject(sprintf("%s , la Concordia te souhaite un joyeux anniversaire", $recipientName))
                    ->html(
                        "<html>
                            <body>
                                <p>Bonjour $recipientName,</p>
                                <p>Nous vous souhaitons un joyeux anniversaire de la part de toute l'équipe Concordia.</p>
                                <p style=\"text-align: center;\">
                                    <img src='" . $message->embed(public_path('assets/images/Anniv-Mail-resize.jpg')) . "' alt='Image_anniversare' />
                                </p>
                                <p style=\"text-align: center;\">
                                    Si ce mail ne devait pas charger correctement, veuillez cliquer sur le lien ci-dessous :
                                </p>
                                <p style=\"text-align: center;\">
                                    <a href='" . route('anniversaire') . "'>Page Anniversaire</a>
                                </p>
                                <p>Cordialement,<br>Gym Concordia</p>
                            </body>
                        </html>"
                    );
            });
        }
    }
}

function updateTotalCharges($bill_id)
{
    $messages = ShopMessage::where('id_bill', $bill_id)->get();
    $total_payed = 0;
    foreach ($messages as $message) {
        $total_payed += $message->somme_payé;
    }
    $bill = bills::find($bill_id);
    if (!$bill) {
        $bill = old_bills::find($bill_id);
    }

    if (!$bill) {
        // Handle case when bill is not found in both tables
        throw new Exception('Bill not found');
    }

    $bill->amount_paid = $total_payed;
    $bill->save();
    return $total_payed;
}



// recuperer l'ID d'un shop articles et retourne les USERS qui ont achete le produit a une date anterieure
function Donne_User_article_Date($id_shop_article, $date1)
{

    $shop_article_with_date = DB::table('bills')->where('status', '>', 60)->Where('id_shop_article', $id_shop_article)->whereDate('date_bill', '<', $date1)
        ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_user')->unique()->toArray();

    return  $shop_article_with_date;
}


function Donne_User_article_Paye($id_shop_article)
{

    $shop_article = DB::table('bills')->where('status', '>', 60)->Where('id_shop_article', $id_shop_article)
        ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')->pluck('id_user')->unique()->toArray();

    return  $shop_article;
}




// fonction pour determiner les destinataires des mails

function destinataires_du_mail($user_id)
{

    $tab = [];
    $tab1 = [];

    //recupere le famiily level pour verifier si l'user est un child ou un parent
    $requete_user_family_level = DB::table('users')->where('user_id', $user_id)->value('family_level');
    //recupere l'email du user
    $requete_user_email = DB::table('users')->where('user_id', $user_id)->value('email');

    if ($requete_user_family_level == "parent") {
        $tab[] = $user_id;

        return $tab;
    } else if (($requete_user_family_level == "child") and ((!isset($requete_user_email)) or ($requete_user_email == NULL))) {

        $requete_user_family_id = DB::table('users')->where('user_id', $user_id)->value('family_id');
        $tab = DB::table('users')->where('family_id', $requete_user_family_id)->where('family_level', 'parent')->pluck('user_id')->toArray();

        foreach ($tab as $t) {
            if ($t != $user_id) {
                $tab1[] = $t;
            }
        }
        return $tab1;
    } else if (($requete_user_family_level == "child") and (isset($requete_user_email))) {

        $requete_user_family_id = DB::table('users')->where('user_id', $user_id)->value('family_id');
        $tab = DB::table('users')->where('family_id', $requete_user_family_id)->where('family_level', 'parent')->pluck('user_id')->toArray();

        return $tab;
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

function receiveEmailFromUser(Request $request, $email_destinataire)
{
    $email = $request->input('email');
    $message = $request->input('message');
    $nom = $request->input('name');


    Mail::raw($message, function ($message) use ($email, $email_destinataire, $nom) {
        $message->from(config('mail.from.address'), config('mail.from.name'))
            ->to($email_destinataire)
            ->subject('[' . $nom . '] Message d\'un utilisateur')
            ->replyTo($email);
    });



    //dd($email);


}

/* -------------------------another Email function  ------------------------------- */



function envoiEmail($userEmail, $message, $receiverEmail, $userName)
{


    // Set the SMTP credentials dynamically
    $config = [
        'driver' => config('mail.driver'),
        'host' => config('mail.host'),
        'port' => config('mail.port'),
        'from' => ['address' => $userEmail, 'name' => $userName],
        'encryption' => config('mail.encryption'),
        'username' => config('mail.username'),
        'password' => config('mail.password')
    ];
    Mail::mailer('smtp')->to($receiverEmail)->send(new ContactFormMail($userEmail, $message, $userName));
}




class ContactFormMail  extends \Illuminate\Mail\Mailable
{


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
            ->subject('[' . $this->userName . '] Message d\'un utilisateur')
            ->view('Communication/form_email')
            ->with([
                'content_Email' => $this->message,
            ]);
    }
}




function envoiEmail2($userEmail, $message, $receiverEmail, $userName, $titre)
{


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



    Mail::mailer('smtp')->to($receiverEmail)->send(new ContactFormMail_module_com($userEmail, $message, $userName, $titre));
}



class ContactFormMail_module_com  extends \Illuminate\Mail\Mailable
{


    public $userEmail;
    public $message;
    public $userName;
    public $titre;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userEmail, $message, $userName, $titre)
    {
        $this->userEmail = $userEmail;
        $this->message = $message;
        $this->userName = $userName;
        $this->titre    = $titre;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->from($this->userEmail, $this->userName)
            ->subject('[' . $this->userName . '] Message d\'un utilisateur')
            ->view('Communication/emailbody')
            ->with([
                'message1' => $this->message,
                'the_title' => $this->titre
            ]);
    }
}








//fonctions pour afficher les dates en Anglais
function fetchDay($date)
{

    $lejour = (new DateTime($date))->format('l');

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


/*
Cette fonction vérifie si l'utilisateur est connecté elle filtre les réductions pour garder celles qui sont liées à l'utilisateur
 connecté ou celles qui ne sont liées à aucun utilisateur.
Si l'utilisateur n'est pas connecté, la fonction filtre  les réductions pour ne garder que celles qui ne sont liées à aucun utilisateur.
*/
function getReducedPrice($articleId, $originalPrice, $user_id)
{
    $reducedPrice = $originalPrice;
    $valueReductions = [];
    $percentageReductions = [];

    if (Auth::check() && $user_id) {
        $userId = $user_id;

        // Fetch all user-specific reductions
        $userReductions = UserReductionUsage::where('user_id', $userId)
            ->where('shop_article_id', $articleId)
            ->whereColumn('usage_count', '<', 'usage_max')
            ->get();

        foreach ($userReductions as $userReduction) {
            $reduction = ShopReduction::find($userReduction->reduction_id);
            if ($reduction && $reduction->state === 1 && now()->between($reduction->startvalidity, $reduction->endvalidity)) {
                if ($reduction->value != 0) {
                    $valueReductions[] = $reduction->value;
                } elseif ($reduction->percentage != 0) {
                    $percentageReductions[] = $reduction->percentage;
                }
            }
        }
    }


    if (empty($valueReductions) && empty($percentageReductions)) {
        // If no user-specific reductions are found, get general reductions for the article
        $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();
        foreach ($shopReductions as $shopReduction) {
            $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->whereNull('destroy')
                ->where('state', 1)
                ->whereDate('startvalidity', '<=', now())
                ->whereDate('endvalidity', '>=', now())
                ->first();

            if ($reduction) {
                if ($reduction->value != 0) {
                    $valueReductions[] = $reduction->value;
                } elseif ($reduction->percentage != 0) {
                    $percentageReductions[] = $reduction->percentage;
                }
            }
        }
    }

    // Calculate total value reduction
    $totalValueReduction = array_sum($valueReductions);

    // Calculate total percentage reduction
    $maxPercentageReduction = !empty($percentageReductions) ? max($percentageReductions) : 0;
    $totalPercentageReduction = $originalPrice * ($maxPercentageReduction / 100);

    // Use the greater reduction
    $totalReduction = $totalValueReduction > $totalPercentageReduction ? $totalValueReduction : $totalPercentageReduction;

    return $totalReduction;
}

function incrementReductionUsageCount($paniers)
{
    foreach ($paniers as $panier) {
        // Check if the article is associated with a user reduction
        $userReductionUsage = UserReductionUsage::where('shop_article_id', $panier->ref)
            ->where('user_id', auth()->user()->user_id)
            ->first();

        if ($userReductionUsage) {
            // Increment the usage_count by 1
            $userReductionUsage->increment('usage_count');
        }
    }
}


function getFirstReductionDescription($articleId, $user_id)
{
    $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();
    $reductionDescription = '';

    // Check if user is authenticated
    if (Auth::check()) {
        $userId = $user_id;
        $shopReductions = $shopReductions->filter(function ($shopReduction) use ($userId) {
            // Keep reductions linked to authenticated user or not linked to any user
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhereNull('user_id');
                })
                ->first();
            // Keep reductions not linked to any user
            $noUserLiaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first() === null;
            return $liaison !== null || $noUserLiaison;
        });
    } else {
        // Filter out reductions linked to any user
        $shopReductions = $shopReductions->filter(function ($shopReduction) {
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first();
            return !$liaison;
        });
    }

    // Get description of first reduction
    foreach ($shopReductions as $shopReduction) {
        $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
            ->whereNull('destroy')
            ->where('state', 1)
            ->whereDate('startvalidity', '<=', now())
            ->whereDate('endvalidity', '>=', now())
            ->first();

        if ($reduction) {
            $reductionDescription = $reduction->description;
            break;
        }
    }

    return $reductionDescription;
}

function getFirstReductionDescriptionGuest($articleId)
{
    $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();
    $reductions = collect();

    if (Auth::check()) {
        $userId = Auth::user()->user_id;

        // Récupérez d'abord toutes les réductions spécifiques à l'utilisateur
        $userReductions = UserReductionUsage::where('user_id', $userId)
            ->where('shop_article_id', $articleId)
            ->whereColumn('usage_count', '<', 'usage_max')
            ->get();

        foreach ($userReductions as $userReduction) {
            $reduction = ShopReduction::find($userReduction->reduction_id);
            if ($reduction && $reduction->state === 1 && now()->between($reduction->startvalidity, $reduction->endvalidity)) {
                $reductions->push($reduction);
            }
        }
    }

    if ($reductions->isEmpty()) {
        foreach ($shopReductions as $shopReduction) {
            $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->whereNull('destroy')
                ->where('state', 1)
                ->whereDate('startvalidity', '<=', now())
                ->whereDate('endvalidity', '>=', now())
                ->first();

            if ($reduction) {
                $reductions->push($reduction);
            }
        }
    }

    return $reductions->first()->description ?? '';
}


function getReducedPriceGuest($articleId, $originalPrice)
{
    $reducedPrice = $originalPrice;
    $valueReductions = [];
    $percentageReductions = [];

    if (Auth::check()) {
        $userId = Auth::user()->user_id;

        // Récupérez d'abord toutes les réductions spécifiques à l'utilisateur
        $userReductions = UserReductionUsage::where('user_id', $userId)
            ->where('shop_article_id', $articleId)
            ->whereColumn('usage_count', '<', 'usage_max')
            ->get();
        foreach ($userReductions as $userReduction) {
            $reduction = ShopReduction::find($userReduction->reduction_id);
            if ($reduction && $reduction->state === 1 && now()->between($reduction->startvalidity, $reduction->endvalidity)) {
                if ($reduction->value != 0) {
                    $valueReductions[] = $reduction->value;
                } elseif ($reduction->percentage != 0) {
                    $percentageReductions[] = $reduction->percentage;
                }
            }
        }
    }

    if (empty($valueReductions) && empty($percentageReductions)) {
        $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();

        foreach ($shopReductions as $shopReduction) {
            $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->whereNull('destroy')
                ->where('state', 1)
                ->whereDate('startvalidity', '<=', now())
                ->whereDate('endvalidity', '>=', now())
                ->first();

            if ($reduction) {
                if ($reduction->value != 0) {
                    $valueReductions[] = $reduction->value;
                } elseif ($reduction->percentage != 0) {
                    $percentageReductions[] = $reduction->percentage;
                }
            }
        }
    }

    // Apply value reductions
    foreach ($valueReductions as $valueReduction) {
        $reducedPrice -= $valueReduction;
    }

    // Apply largest percentage reduction
    if (!empty($percentageReductions)) {
        $maxPercentageReduction = max($percentageReductions);
        $reducedPrice *= (1 - ($maxPercentageReduction / 100));
    }

    return $reducedPrice;
}



function applyFamilyDiscount(Shop_article $article, $pour_user_id)
{
    $reductionSetting = SystemSetting::where('name', 'reduction_famille')->first();
    if (!$reductionSetting || $reductionSetting->value != '1') {
        return;
    }

    $pour_user_id = intval($pour_user_id);
    $user = User::find(auth()->user()->user_id);
    $family_id = $user->family_id;

    $family_members = User::where('family_id', $family_id)->get();
    $member_found = false;
    foreach ($family_members as $member) {
        if (isUserMember($member->user_id) > 0) {
            $member_found = true;
        }
    }

    $userBasketItem = false;
    if ($article->type_article == 0) {
        $userBasketItem = true;
    }

    $userFamilyDiscount = Basket::where('pour_user_id', $pour_user_id)
        ->where('ref', '1')
        ->first();

    $basket_mem = false;
    foreach ($family_members as $member) {
        if ($member->user_id != $pour_user_id) {
            $memberBasketItem = Basket::join('shop_article', 'basket.ref', '=', 'shop_article.id_shop_article')
                ->where('basket.user_id', auth()->user()->user_id)
                ->where('basket.pour_user_id', $member->user_id)
                ->where('shop_article.type_article', 0)
                ->first();
        }

        $memberFamilyDiscount = Basket::where('user_id', auth()->user()->user_id)
            ->where('pour_user_id', intval($member->user_id))
            ->where('ref', '1')
            ->first();

        if (isset($memberBasketItem) && $memberBasketItem && !$memberFamilyDiscount) {
            $basket_mem = true;
            break;
        }
    }

    if ($member_found || $basket_mem) {
        $saison = saison_active();
        $reduction_famille = DB::table('parametre')
            ->select('reduction_famille')
            ->where('saison', $saison)
            ->first()
            ->reduction_famille;

        $shopArticle = Shop_article::find(1);
        $shopArticle->totalprice = $reduction_famille * (-1);
        $shopArticle->save();

        $basket = new Basket([
            'user_id' => auth()->user()->user_id,
            'family_id' => $family_id,
            'pour_user_id' => $pour_user_id,
            'ref' => '1',
            'qte' => 1,
            'prix' => $reduction_famille * (-1),
        ]);
        $basket->save();
    }
}












function envoiBillInfoMail($userEmail, $message, $receiverEmail, $userName, $paniers, $total, $nb_paiment, $payment, $bill, $text)
{


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

    Mail::mailer('smtp')->to($receiverEmail)->send(new BillInfoMail($userEmail, $message, $userName, $paniers, $total, $nb_paiment, $payment, $bill, $text));
}

class BillInfoMail extends \Illuminate\Mail\Mailable
{

    public $userEmail;
    public $message;
    public $userName;
    public $paniers;
    public $total;
    public $nb_paiment;
    public $payment;
    public $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userEmail, $message, $userName, $paniers, $total, $nb_paiment, $payment, $bill, $text)
    {
        $this->userEmail = $userEmail;
        $this->message = $message;
        $this->userName = $userName;
        $this->paniers = $paniers;
        $this->total = $total;
        $this->nb_paiment = $nb_paiment;
        $this->payment = $payment;
        $this->bill = $bill;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return  $this->from($this->userEmail, $this->userName)
            ->view('emails.bill_info')

            ->subject('En attente de paiement par ' . $this->payment)
            ->with([
                'userEmail' => $this->userEmail,
                'message' => $this->message,
                'userName' => $this->userName,
                'paniers' => $this->paniers,
                'total' => $this->total,
                'nb_paiment' => $this->nb_paiment,
                'payment' => $this->payment,
                'bill' => $this->bill,
                'text' => $this->text,
            ]);
    }
}

/*----------------------------Index Admin chiffre d'affaire, Reste, nbres Inscrits,--------------------------------*/
function count_CA()
{
    $dateRentrée = Parametre::where('saison', saison_active())->first()->date_de_rentree;

    $factures = bills::where('status', '>', 9)
        ->where('date_bill', '>=', $dateRentrée)
        ->get();

    $montantTotalNonPayé = $factures->sum('payment_total_amount');

    return $montantTotalNonPayé;
}


function count_reste_CA()
{
    $dateRentrée = Parametre::where('saison', saison_active())->first()->date_de_rentree;
    $factures = bills::where('status', '>', 9)
        ->where('status', '<', 41)
        ->where('date_bill', '>=', $dateRentrée)
        ->get();

    $montantTotalNonPayé = $factures->sum('payment_total_amount');
    return $montantTotalNonPayé;
}


function inscrits()
{

    $saison = saison_active();

    $result = DB::table('liaison_shop_articles_bills')->select('liaison_shop_articles_bills.id_user')
        ->leftjoin('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
        ->leftjoin('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('type_article', 0)
        ->where('type', 'facture')
        ->where('status', '>', 9)
        ->where('saison', $saison)
        ->distinct()
        ->count('liaison_shop_articles_bills.id_user');

    /*   $result = $this->db->query("SELECT COUNT(*) as cc FROM `liaison_shop_articles_bills` LEFT JOIN `bills` 
       ON bills.id_bill = liaison_shop_articles_bills.id_bill 
       WHERE liaison_shop_articles_bills.id_shop_article = '$row->id_article_inscription'
        AND bills.type = 'facture' AND bills.state != 'Commande suspendue'");  */

    return  $result;
}



function nbr_inscrits_based_on_date($saison)
{
    $periodes = [];
    $date_today = date("Y-m-d");
    $currentYear = date("Y");
    $todayMonthDay = date("m-d");
    if ($todayMonthDay < "06-20") {
        $season_start_date = date("Y-m-d 00:00:00", strtotime(($saison) . "-06-20"));
        $season_end_date = date("Y-m-d 23:59:59", strtotime($saison + 1 . "-" . $todayMonthDay));
    } else {
        $season_start_date = date("Y-m-d 00:00:00", strtotime($saison . "-06-20"));
        $season_end_date = date("Y-m-d 23:59:59", strtotime($saison . "-" . $todayMonthDay));
    }
    $periodes[] = "Période : " . $season_start_date . " à " . $season_end_date;
    $result = DB::table('liaison_shop_articles_bills')->select('liaison_shop_articles_bills.id_user')
        ->leftjoin('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
        ->leftjoin('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('type_article', 0)
        ->where('type', 'facture')
        ->where('status', '>', 9)
        ->where('saison', $saison)
        ->whereBetween('date_bill', [$season_start_date, $season_end_date])
        ->distinct()
        ->count('liaison_shop_articles_bills.id_user');
    $result_old_bills = DB::table('liaison_shop_articles_bills')->select('liaison_shop_articles_bills.id_user')
        ->leftjoin('old_bills', 'liaison_shop_articles_bills.bill_id', '=', 'old_bills.id')
        ->leftjoin('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('type_article', 0)
        ->where('type', 'facture')
        ->where('status', '>', 9)
        ->where('saison', $saison)
        ->whereBetween('date_bill', [$season_start_date, $season_end_date])
        ->distinct()
        ->count('liaison_shop_articles_bills.id_user');
    $final_result =  $result + $result_old_bills;
    return ['result' => $final_result, 'periodes' => $periodes];
}





function generateArray($start, $end, $step)
{
    $array = array();
    for ($i = $start; $i <= $end; $i += $step) {
        $array[] = $i;
    }
    return $array;
}

// pour pouvoir remplacer l'url path par le nom de page pour les stats
function put_label($url)
{

    $translate_tab = array(
        "/" => "index",
        "Categorie_front" => "Nos catégories",
        "SubCategorie_front/1" => "Inscriptions 2022-2023",
        "SubCategorie_front/2" => "Stages Vacances",
        "SubCategorie_front/3" => "Evénements/Prestations",
        "SubCategorie_front/4" => "Dons à l'Association",
        "SubCategorie_front/5" => "Boutique",

        "SubCategorie_front/200" => "Vacances Eté",
        "SubCategorie_front/201" => "Petites Vacances",
        "SubCategorie_front/202" => "Stages Semaine - 5-9 ans",
        "SubCategorie_front/203" => "Stages Semaine - + 10 ans",
        "SubCategorie_front/204" => "Stages Journée - 5-9 ans",

        "SubCategorie_front/100"   => "Petite Enfance",
        "SubCategorie_front/1001" => "Mini-BabyGym (1 An)",
        "SubCategorie_front/1002" => "Baby Gym (2 Ans)",
        "SubCategorie_front/1003" => "Eveil Gymnique (3 Ans)",
        "SubCategorie_front/1004" => "Ecole de Gym (4-5 Ans)",


        "SubCategorie_front/120"   => "Loisirs",
        "SubCategorie_front/1200" => "Aérobic Sportive",
        "SubCategorie_front/1201" => "Fitness Kids",
        "SubCategorie_front/1202" => "Gym Acrobatique",
        "SubCategorie_front/1203" => "Parkour Jeunes",
        "SubCategorie_front/1204" => "Gym Rythmique",
        "SubCategorie_front/1205" => "Gym Masculine",
        "SubCategorie_front/1206" => "Gym Féminine",
        "SubCategorie_front/1207" => "Arts du Cirque",

        "SubCategorie_front/130"   => "Adultes",
        "SubCategorie_front/1300" => "Renf. Musculaire",
        "SubCategorie_front/1301" =>  "Séances Cardio CAF",
        "SubCategorie_front/1303" => "Zumba",
        "SubCategorie_front/1304" => "Séances [Visio]",
        "SubCategorie_front/1305" => "Pilates",
        "SubCategorie_front/1306" => "Step CAF",
        "SubCategorie_front/1307" => "Cross Training",
        "SubCategorie_front/1308" => "Aerobic Fitness",

        "SubCategorie_front/140"   => "Marche Nordique",
        "SubCategorie_front/1501"   => "Séniors",
        "SubCategorie_front/1502"   => "Handi-Gym",
        "SubCategorie_front/1503"   => "Sport Santé",
        "SubCategorie_front/1504"   => "Yoga",
        "SubCategorie_front/1505"   => "Stretching",
        "SubCategorie_front/1506"   => "Attente",

    );


    foreach ($translate_tab as $key => $value) {

        if ($key == $url) {
            return $value;
        }
    }

    return $url;
}

/* -------------------- add colors --------------------- */

function color_de_row($id_article, $id_user)
{




    $result = DB::table('liaison_shop_articles_bills')->select('bills_status.row_color')
        ->join('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
        ->join('bills_status', 'bills_status.id', '=', 'bills.status')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('liaison_shop_articles_bills.id_shop_article', $id_article)
        ->where('liaison_shop_articles_bills.id_user', $id_user)
        ->first();

    if ($result) {

        return $result->row_color;
    } else {

        return 'Lime';

        // handle the case where the query did not return any results
    }
}



/*----------------- fonction pour sauvegarder historiques des membres -------------- */

function History_member($saison)
{



    $id_produit_inscription = DB::table('parametre')->where('saison', $saison)->value('id_article_inscription');

    $result = DB::table('liaison_shop_articles_bills')->select('liaison_shop_articles_bills.id_shop_article', 'users.name', 'users.lastname', 'users.user_id', 'users.birthdate',)
        ->join('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
        ->join('users', 'users.user_id', '=', 'liaison_shop_articles_bills.id_user')
        ->where('liaison_shop_articles_bills.id_shop_article', $id_produit_inscription)
        ->where('bills.status', '>', 50)
        ->get();



    foreach ($result as $value) {

        $member_history = new member_history; // creation d'une nouvelle instance a chaque iteration

        $member_history->id_user =  $value->user_id;
        $member_history->nom =  $value->name;
        $member_history->prenom =  $value->lastname;
        $member_history->date_naissance =  $value->birthdate;
        $member_history->saison = $saison;

        $member_history->save();
    }

    return redirect()->back()->with('user', auth()->user())->with('success', 'Opération reussie');
}

// ----------------- updateArticleCategories --------------
function updateArticleCategories($shopArticleId, array $shopCategoryIds)
{
    DB::table('liaison_shop_articles_shop_categories')
        ->where('id_shop_article', $shopArticleId)
        ->delete();

    $data = [];
    foreach ($shopCategoryIds as $categoryId) {
        $data[] = [
            'id_shop_article' => $shopArticleId,
            'id_shop_category' => $categoryId,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    DB::table('liaison_shop_articles_shop_categories')->insert($data);
}

function updateTotalPrice(Shop_article $article)
{
    $shopArticle = Shop_article::find($article->need_member);
    if ($shopArticle) {
        $article->totalprice = $article->price + $shopArticle->totalprice;
        $article->save();
    } else {
        $article->totalprice = $article->price;
        $article->save();
    }
}


function generateSignature($data, $key, $algorithm = "HMAC-SHA-256")
{
    // Triez les champs dont le nom commence par vads_ par ordre alphabétique
    ksort($data);
    // Assurez-vous que tous les champs soient encodés en UTF-8
    $data = array_map('utf8_encode', $data);

    // Concaténez les valeurs de ces champs en les séparant avec le caractère "+"
    $data = implode("+", $data);
    // Concaténez le résultat avec la clé de test ou de production en les séparant avec le caractère "+"
    $data = $data . "+" . $key;

    if ($algorithm == "SHA-1") {
        // Appliquez la fonction de hachage SHA-1 sur la chaîne obtenue à l'étape précédente
        return sha1($data);
    } else if ($algorithm == "HMAC-SHA-256") {
        // Calculez et encodez au format Base64 la signature du message en utilisant l'algorithme HMAC-SHA-256
        return base64_encode(hash_hmac('sha256', $data, $key, true));
    }

    return null;
}

function remove_accents($str)
{
    return iconv('UTF-8', 'ASCII//TRANSLIT', $str);
}


function chiffreEnLettre($nombre)
{
    $unites = ['zéro', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];
    $dizaines = ['', '', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix'];

    if ($nombre < 20) {
        return $unites[$nombre];
    } elseif ($nombre < 100) {
        if ($nombre % 10 == 0) {
            return $dizaines[$nombre / 10];
        } else if ($nombre < 70) {
            return $dizaines[$nombre / 10] . '-' . $unites[$nombre % 10];
        } else if ($nombre < 80) {
            return 'soixante-' . chiffreEnLettre($nombre % 20);
        } else {
            return 'quatre-vingt-' . chiffreEnLettre($nombre % 20);
        }
    } elseif ($nombre < 1000) {
        if ($nombre == 100) {
            return 'cent';
        } else {
            return ($nombre < 200 ? 'cent' : chiffreEnLettre((int)($nombre / 100)) . ' cent') . ($nombre % 100 > 0 ? ' ' . chiffreEnLettre($nombre % 100) : '');
        }
    } elseif ($nombre < 1000000) {
        if ($nombre == 1000) {
            return 'mille';
        } else {
            return ($nombre < 2000 ? 'mille' : chiffreEnLettre((int)($nombre / 1000)) . ' mille') . ($nombre % 1000 > 0 ? ' ' . chiffreEnLettre($nombre % 1000) : '');
        }
    } else {
        return 'Nombre trop grand';
    }
}

function fetchMonth($date)
{

    $lemois = (new DateTime($date))->format('n');

    $months = array(
        1 =>  'Janvier',
        2 => 'Fevrier',
        3 =>  'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 =>  'Juin',
        7 => 'Juillet ',
        8 => 'Aout',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 =>  'Decembre',
    );


    foreach ($months as $key => $j) {

        if ($key == $lemois) {
            return $j;
        }
    }
}

function fetchan($date)
{

    $an = (new DateTime($date))->format('Y');

    return $an;
}
function fetchjour($date)
{

    $jour = (new DateTime($date))->format('d');

    return $jour;
}

function fetchDayName($date)
{
    $dayName = (new DateTime($date))->format('l');
    $days = array(
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    );
    return $days[$dayName];
}

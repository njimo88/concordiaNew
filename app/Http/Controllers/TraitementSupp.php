<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\A_Blog_Post;
use App\Models\Category;
use App\Models\Shop_article;
use App\Models\Shop_category;
use App\Models\shop_article_1;
use App\Models\shop_article_2;
use App\Models\Room;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\bills;
use App\Models\liaison_shop_articles_bills;
use App\Models\PaiementImmediat;


use DateTime;
require_once(app_path().'/fonction.php');

use Illuminate\Support\Facades\DB;

class TraitementSupp extends Controller
{
    public function fusionsql()
    {
        
        $posts = A_Blog_Post::all();

        $blogPosts = A_Blog_Post::all();
        foreach ($blogPosts as $post) {
            $categorie1 = json_decode($post->categorie);
            $categorie2 = json_decode($post->categorie);
            
            // Merge the two arrays and re-encode to JSON
            $mergedCategories = array_merge($categorie1, $categorie2);
            $post->categorie = json_encode($mergedCategories);
            
            $post->save();
        }
        dd ('ok');
    }

    public function jsoncorrection()
    {
        
        $blogPosts = A_Blog_Post::all();
        foreach ($blogPosts as $post) {
            $categorie = json_decode($post->categorie);

            for ($i = 0; $i < count($categorie); $i++) {
                if ($categorie[$i] >= 20 && $categorie[$i] <= 28) {
                    $categorie[$i] += 200;
                } 
                if ($categorie[$i] >= 60 && $categorie[$i] <= 85) {
                    $categorie[$i] += 100;
                }
            }

            // Re-encode to JSON and save
            $post->categorie = json_encode($categorie);
            $post->save();
        }

        dd ('ok');
    }

    public function fusionnertable()
    {
        $categorie1s = DB::table('categorie1')->get();
        $categorie2s = DB::table('categorie2')->get();

        foreach ($categorie1s as $categorie1) {
            DB::table('categories')->insert([
                'id_categorie' => $categorie1->Id_categorie,
                'nom_categorie' => $categorie1->nom_categorie,
                'description' => $categorie1->description,
                'categorie_URL' => $categorie1->categorie_URL,
                'image' => $categorie1->image,
                'visibilite' => $categorie1->visibilite,
                'created_at' => $categorie1->created_at,
                'updated_at' => $categorie1->updated_at
            ]);
        }

        foreach ($categorie2s as $categorie2) {
            DB::table('categories')->insert([
                'id_categorie' => $categorie2->Id_categorie,
                'nom_categorie' => $categorie2->nom_categorie,
                'description' => $categorie2->description,
                'categorie_URL' => $categorie2->categorie_URL,
                'image' => $categorie2->image,
                'created_at' => $categorie2->created_at,
                'updated_at' => $categorie2->updated_at
            ]);
        }

        dd ('ok');
    }


    public function carouselblog()
    {
        $posts = A_Blog_Post::latest()
        ->where('status', '=', 'Publié')
        ->paginate(5);

        $categorie = Category::all();

        $saison  = saison_active();

        $all_valid_articles = Shop_article::where('type_article', '=', '2')
            ->where('saison', '=', $saison)
            ->get();

        $filtered_articles = filterArticlesByValidityDate($all_valid_articles);

        $shop_articles = $filtered_articles->shuffle()->take(7);

 

        // Récupérer les images du répertoire Slider
        $images = File::files(public_path('uploads/Slider'));
        $imageUrls = [];

        foreach ($images as $image) {
            if (in_array($image->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) { // Vous pouvez ajouter d'autres extensions si nécessaire.
                $imageUrls[] = asset('uploads/Slider/' . $image->getFilename());
            }
        }
        

        return view('carousel', compact('posts', 'categorie', 'shop_articles', 'imageUrls'));
    }


    public function tousLesArticles()
{
    $posts = A_Blog_Post::latest()
        ->where('status', '=', 'Publié')
        ->paginate(9);

    $categorie = Category::all();

    return view('tousLesArticles', compact('posts', 'categorie'));
}

public function blog ($id )
{
    $posts = A_Blog_Post::FindOrFail($id);

    $categorie = Category::all();
    
    $carouselle =  A_Blog_Post::latest()
    ->where('status', '=', 'Publié')
    ->paginate(5);

    return view('blog', compact('posts', 'categorie', 'carouselle'));
}

public function shop ()
{
    $shop_articles = Shop_article::take(12)
    ->where('type_article', '=', '2')
    ->get();
    return view('shop', ['shop_articles' => $shop_articles]);
}

public function shop_categories ()
{
    $shop_categories = Shop_category::where('id_shop_category', '<=', '9')->orderBy('order_category', 'ASC')->get();
    return view('shop_categories', ['shop_categories' => $shop_categories]);

}


public function sub_shop_categories($id)
{
    MiseAjourStock();
    $indice = $id;
    $info = Shop_category::where('active', 1)->get();
    $breadcrumb = $this->getBreadcrumb($indice, $info);
    $info2 = Shop_category::select('name','description')->where('id_shop_category','=',$indice)->first();

    // Vérification de la dernière sous-catégorie
    $isLastSubCategory = !$info->where('id_shop_category_parent', $indice)->count();
    $display_product = $isLastSubCategory;

    // Redirection si c'est la dernière sous-catégorie
    if($display_product) {
        return redirect()->route('boutique', ['id' => $indice]);
    }

    return view('sub_shop_categories', compact('info', 'breadcrumb', 'indice', 'info2' ));
}


public function getBreadcrumb($categoryId, $categories) 
{
    $breadcrumb = [];
    while ($categoryId) 
    {
        $currentCategory = $categories->firstWhere('id_shop_category', $categoryId);
        if ($currentCategory) 
        {
            $breadcrumb[] = $currentCategory;
            $categoryId = $currentCategory->id_shop_category_parent;
        } 
        else 
        {
            break;
        }
    }
    return array_reverse($breadcrumb);
}



public function boutique($id)
{
    $info = Shop_category::where('active', 1)->get();
    $breadcrumb = $this->getBreadcrumb($id, $info);
    $info2 = Shop_category::select('name','description')->where('id_shop_category','=',$id)->first();
    $saison_active = saison_active();
    $articles = Shop_article::getArticlesByCategories($id, $saison_active);
    return view('boutique', compact('articles' ,'breadcrumb' ,'info2' ));
}

public function singleProduct($id) {
    MiseAjourStock();

    // Obtenez l'article initial avec son ID
    $articl = Shop_article::where('id_shop_article', $id)->firstOrFail();
    
    // Effectuez une jointure en fonction du type d'article
    if ($articl->type_article == 1) {
        $articl = Shop_article::where('shop_article.id_shop_article', $id)
            ->join('shop_article_1', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')
            ->firstOrFail();

            $teacherIds = json_decode($articl->teacher, true);
            $teachers = User::whereIn('user_id', $teacherIds)->get();

            $schedules = [];  // Cette liste va contenir toutes les plages horaires
            if(isset($articl->lesson)) {
                $Data_lesson = json_decode($articl->lesson,true);
                foreach($Data_lesson['start_date'] as $index => $startDate) {
                    $startHour = (new DateTime($startDate))->format('H:i');
                    $endHour = (new DateTime($Data_lesson['end_date'][$index]))->format('H:i');
                    $dayName = (new DateTime($startDate))->format('l');
                    $dayName = fetchDayName($dayName);
                    $schedules[] = "$dayName de $startHour à $endHour";
                }
            }

            $rooms = Room::whereIn('id_room', $Data_lesson['room'])->get();
            $locations = [];  // Cette liste va contenir tous les noms des salles

            foreach($Data_lesson['room'] as $roomId) {
                $room = $rooms->where('id_room', $roomId)->first();
                if($room) {
                    $locations[] = $room->name;  // Remplacez 'id_name' par 'name'
                }
            }

            

    } elseif ($articl->type_article == 2) {
        $articl = Shop_article::where('shop_article.id_shop_article', $id)
            ->join('shop_article_2', 'shop_article.id_shop_article', '=', 'shop_article_2.id_shop_article')
            ->firstOrFail();
    }
    
    $selectedUsers = array();
    $coursVente = SystemSetting::find(5);
    
    if (Auth::check()) {
        $selectedUsers = getArticleUsers($articl);
    }
    
    if ($articl->type_article == 1){
        return view('singleProduct', compact('articl', 'teachers', 'schedules', 'locations', 'selectedUsers', 'coursVente'));
    } else {
        return view('singleProduct', compact('articl', 'selectedUsers', 'coursVente'));
    }

}

public function basket (){
    if (Auth::check()){

        $paniers = DB::table('basket')
    ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
    ->join('shop_article', 'shop_article.id_shop_article', '=', 'basket.ref')
    ->leftJoin('shop_article_2', 'shop_article_2.id_shop_article', '=', 'basket.ref')
    ->where('basket.user_id', '=', auth()->user()->user_id)
    ->groupBy('basket.pour_user_id', 'shop_article_2.declinaison', 'basket.declinaison', 'basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref', 'users.name', 'users.lastname', 'basket.reduction')
    ->orderBy('basket.pour_user_id')
    ->orderBy('basket.ref')
    ->select('basket.user_id', 'basket.declinaison', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(shop_article_2.declinaison, '$[0].libelle')) as declinaison_libelle"), 'basket.reduction')
    ->get();

        $total = 0;
        foreach ($paniers as $panier) {
            $total += $panier->total_qte * $panier->prix;
        }
        // Retourner la vue avec les données récupérées
        return view('basket', compact('paniers','total'))->with('user', auth()->user());
    }else{
        return redirect()->route('login');
    }
}

public function paiement(){

    $Mpaiement = DB::table('bills_payment_method')->get();
    $order = [1,3,5,4,6,2];
    $Mpaiement = $Mpaiement->sortBy(function($item) use ($order) {
        return array_search($item->id, $order);
    });
    $user_id = auth()->user()->user_id;
    $adresse = DB::table('users')
            ->select('address', 'zip', 'city', 'country')
            ->where('user_id', $user_id)
            ->first();

    $paniers = DB::table('basket')
    ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
    ->join('shop_article', 'basket.ref', '=', 'shop_article.id_shop_article')
    ->select('basket.qte', 'basket.ref', 'shop_article.title', 'shop_article.image', 'shop_article.totalprice', 'shop_article.ref as reff', 'users.name', 'users.lastname')
    ->get();


    MiseAjourArticlePanier($paniers);

    $paniers = DB::table('basket')
    ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
    ->join('shop_article', 'shop_article.id_shop_article', '=', 'basket.ref')
    ->where('basket.user_id', '=', auth()->user()->user_id)
    ->groupBy('basket.pour_user_id', 'basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref', 'users.name', 'users.lastname', 'basket.reduction')
    ->orderBy('basket.pour_user_id')
    ->orderBy('basket.ref')
    ->select('basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix as totalprice', 'basket.reduction', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'))
    ->get();


$total = 0;

foreach ($paniers as $panier) {
    $total += $panier->qte * $panier->totalprice;
}


    $can_purchase = true;
    $unavailable_articles = [];

    foreach ($paniers as $panier) {
        $shop = Shop_article::find($panier->ref); 
        $quantite = $panier->total_qte;
        if (!verifierStockUnArticlePanier($shop, $quantite)) {
            $can_purchase = false;
            $unavailable_articles[] = $shop->title;
        }
    }
    $Espece = DB::table('bills_payment_method')->where('payment_method', 'Espèces')->first();
    $Bons = DB::table('bills_payment_method')->where('payment_method', 'Bons')->first();
    $Cheques = DB::table('bills_payment_method')->where('payment_method', 'Chèques')->first();
    $Virement = DB::table('bills_payment_method')->where('payment_method', 'Virement')->first();
    $cb = DB::table('bills_payment_method')->where('payment_method', 'Carte Bancaire')->first();
    if (!$can_purchase) {
        $error_msg = "Les articles suivants ne peuvent pas être achetés: " . implode(', ', $unavailable_articles);
        return redirect()->back()->withErrors([$error_msg]);
    }else{
        return view('paiement', compact('paniers','total','adresse','Mpaiement','Espece','Bons','Cheques','Virement',"cb"))->with('user', auth()->user());
    }

    }

    public function fichepaiement($id,$nombre_cheques)
    {
        $paniers = DB::table('basket')
    ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
    ->join('shop_article', 'shop_article.id_shop_article', '=', 'basket.ref')
    ->where('basket.user_id', '=',auth()->user()->user_id)
    ->groupBy('basket.pour_user_id', 'basket.user_id','basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'shop_article.totalprice', 'shop_article.ref', 'users.name', 'users.lastname')
    ->orderBy('basket.pour_user_id')
    ->orderBy('basket.ref')
    ->select('basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'shop_article.totalprice', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'))
    ->get();

    

    MiseAjourArticlePanier($paniers);
    $can_purchase = true;
    $unavailable_articles = [];

    foreach ($paniers as $panier) {
        $shop = Shop_article::find($panier->ref); 
        $quantite = $panier->total_qte;
        if (!verifierStockUnArticlePanier($shop, $quantite)) {
            $can_purchase = false;
            $unavailable_articles[] = $shop->title;
        }
    }

    if (!$can_purchase ) {
        $error_msg = "Les articles suivants ne peuvent pas être achetés: " . implode(', ', $unavailable_articles);
        return redirect()->back()->withErrors([$error_msg]);
    }else{
        $paniers = DB::table('basket')
    ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
    ->join('shop_article', 'shop_article.id_shop_article', '=', 'basket.ref')
    ->where('basket.user_id', '=', auth()->user()->user_id)
    ->groupBy('basket.pour_user_id', 'basket.user_id', 'basket.ref', 'basket.qte', 'basket.declinaison', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref', 'users.name', 'users.lastname', 'basket.reduction')
    ->orderBy('basket.pour_user_id')
    ->orderBy('basket.ref')
    ->select('basket.user_id', 'basket.ref', 'basket.qte', 'basket.pour_user_id', 'shop_article.title', 'basket.declinaison', 'shop_article.image', 'basket.prix as totalprice', 'basket.reduction', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'))
    ->get();

        $payment = DB::table('bills_payment_method')->where('id', '=', $id)->first()->payment_method;

    $total = 0;
    foreach ($paniers as $panier) {
        $total += $panier->qte * $panier->totalprice;
    }
    

    if($paniers->count() == 0){
        return redirect()->route('panier');}
        else{
    $bill = new bills;
    $bill->user_id = auth()->user()->user_id;
    $bill->date_bill = date('Y-m-d H:i:s');
    $bill->type = "facture";
    $bill->number = $nombre_cheques;
    $bill->payment_method = $id;
    

    if ($id == 3){
        $bill->status = 32;
    $text = DB::table('bills_payment_method')->where('payment_method', 'Espèces')->first();

    }elseif ($id == 2){
        $bill->status = 38;
        $text = DB::table('bills_payment_method')->where('payment_method', 'Mixte')->first();
    }elseif($id == 4){
    $text = DB::table('bills_payment_method')->where('payment_method', 'Chèques')->first();
    $total += $nombre_cheques;
        $bill->status = 30;
    }elseif ($id == 5){
        $bill->status = 34;
    $text = DB::table('bills_payment_method')->where('payment_method', 'Bons')->first();
    $total += 5;

    }elseif ($id == 6){
        $bill->status = 36;
    $text = DB::table('bills_payment_method')->where('payment_method', 'Virement')->first();

    }elseif ($id == 1){
        $bill->status = 100; 
        $text = DB::table('bills_payment_method')->where('payment_method', 'Carte Bancaire')->first();
    }

    $nb_paiment = calculerPaiements($id,$total,$nombre_cheques);

    $bill->payment_total_amount = $total;
    $bill->family_id = auth()->user()->family_id;
    $bill->ref = "0";
    $bill->save();

    $year = date('Y');
    $billIdWithOffset = $bill->id + 10000;
    $bill->ref = "{$year}-{$billIdWithOffset}";
    
    $bill->save();

    // envoi du mail à la propriétaire de la facture
    $user = auth()->user();
    $receiverEmail = $user->email;
    $userName = 'Gym Concordia [Bureau]';
    $message = "Votre facture n°{$bill->id} a été créée avec succès.";
    $userEmail = "webmaster@gym-concordia.com";
    envoiBillInfoMail($userEmail, $message, $receiverEmail, $userName, $paniers, $total, $nb_paiment, $payment, $bill, $text);

    // envoi du mail Paiement Accepté
    if ($bill->status == 100) {
        $generatePDFController = new generatePDF();  
        $pdfPath = $generatePDFController->generatePDFreduction_FiscaleOutput($bill->id);
        Mail::send('emails.order_accepted', ['user' => $user, 'bill' => $bill], function ($message) use ($receiverEmail, $pdfPath,$bill) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($receiverEmail);
            $message->subject("Paiement accepté - Commande : " . $bill->ref);
        });
    }



    // Ajouter des lignes dans la table de liaison
    foreach ($paniers as $panier) {
        $pou_user = User::where('user_id', $panier->pour_user_id)->first();
        $liaison = new liaison_shop_articles_bills;
        $liaison->bill_id = $bill->id;
        $liaison->href_product = $panier->reff;
        $liaison->quantity = $panier->qte;
        $liaison->ttc = round($panier->totalprice, 2);
        $liaison->addressee = $pou_user->lastname . ' ' . $pou_user->name;
        $liaison->sub_total = round($panier->qte * $panier->totalprice, 2);
        $liaison->designation = $panier->title;
        $liaison->id_shop_article = $panier->ref;
        $liaison->declinaison = $panier->declinaison;
        $liaison->id_user = $pou_user->user_id;
        $liaison->save();
    }

    DB::table('basket')->where('user_id', auth()->user()->user_id)->delete();
    MiseAjourStock();
    return view('postAchat', compact('paniers','total','payment','nb_paiment','bill','text'))->with('user', auth()->user());
    }


    }}

    public function mesfactures()
    {
        $user = Auth::user();

$bill = DB::table('bills')
    ->join('bills_status', 'bills.status', '=', 'bills_status.id')
    ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
    ->where('bills.user_id', $user->user_id)
    ->select('bills.*', 'bills_status.image_status as image_status', 'bills_status.row_color as row_color', 'bills_payment_method.image as image')
    ->union(
        DB::table('old_bills')
        ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
        ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
        ->where('old_bills.user_id', $user->user_id)
        ->select('old_bills.*', 'bills_status.image_status as image_status', 'bills_status.row_color as row_color', 'bills_payment_method.icon as image')
    )->orderBy('date_bill', 'desc')
    ->get();
    
    $family_id = auth()->user()->family_id;

    if(PaiementImmediat::where('family_id', $family_id)->exists()) {
        // Afficher le bouton de paiement
        return view('mesfactures', compact('bill'))->with('user', auth()->user())->with('showPaymentButton', true);
    } else {
        // Masquer le bouton de paiement
        return view('mesfactures', compact('bill'))->with('user', auth()->user())->with('showPaymentButton', false);
    }

    }

    public function mafacture($id)
    {
        $user = auth()->user();
        updateTotalCharges($id);

        $billsQuery = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->where('bills.id', $id)
            ->select('bills.*', 'bills_status.row_color', 'bills_status.status as bill_status', 'users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country', 'users.birthdate', 'bills_payment_method.payment_method as method');

        $oldBillsQuery = DB::table('old_bills')
            ->join('users', 'old_bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
            ->where('old_bills.id', $id)
            ->select('old_bills.*', 'bills_status.row_color', 'bills_status.status as bill_status', 'users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country', 'users.birthdate', 'bills_payment_method.payment_method as method');

        $bill = $billsQuery->union($oldBillsQuery)->first();
        if ($user->belongsToFamily($bill->family_id) || Route::currentRouteName() === 'facture.showBill') {
        
        $shop = DB::table('liaison_shop_articles_bills')
        ->select('id_user','quantity', 'ttc', 'sub_total', 'designation', 'addressee', 'shop_article.image', 'shop_article.id_shop_article', 'liaison_shop_articles_bills.id_liaison')
        ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('bills.id', '=', $id)
        ->orderBy('designation', 'asc')
        ->get()
        ;


        

        $messages = DB::table('shop_messages')
        ->join('users', 'shop_messages.id_admin', '=', 'users.user_id')
        ->where('shop_messages.id_bill', $id)
        ->where('shop_messages.state', 'Public') 
        ->select('shop_messages.message', 'shop_messages.id_shop_message', 'shop_messages.date', 'shop_messages.somme_payé', 'users.name', 'users.lastname','shop_messages.id_customer','shop_messages.id_admin','shop_messages.state')
        ->orderBy('shop_messages.date', 'asc')
        ->get(); 

        $saison  = saison_active();

        $all_valid_articles = Shop_article::where('type_article', '=', '2')
            ->where('saison', '=', $saison)
            ->get();

        $filtered_articles = filterArticlesByValidityDate($all_valid_articles);

        $shop_articles = $filtered_articles->shuffle()->take(7);


        $nb_paiment = calculerPaiements($bill->payment_method,$bill->payment_total_amount,$bill->number);

            return view('mafacture', compact('bill','shop_articles','nb_paiment','shop', 'messages'))->with('user', auth()->user());
        }

        abort(403, 'Vous n\'êtes pas autorisé à accéder à cette facture.');
    }

    public function mafamille(){
        
        $posts = A_Blog_Post::latest()
        ->where('status', '=', 'Publié')
        ->paginate(5);

        $n_users = User::where('family_id', Auth::user()->family_id)->orderBy('family_level', 'desc')->get();
        if (is_null($n_users)) {
            return view('mafamille' ,compact('posts'))->with('user', auth()->user());
        } else {
            return view('mafamille',compact('n_users','posts'))->with('user', auth()->user());
        }
    }


}
<?php

namespace App\Http\Controllers;

use App\Models\bills;
use App\Http\Requests\StorebillsRequest;
use App\Http\Requests\UpdatebillsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\old_bills;
use App\Models\Shop_article;
use App\Models\LiaisonShopArticlesBill;
use App\Models\ShopMessage;
use App\Models\PaiementImmediat;
use PDF;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\ShopReduction;
use App\Models\LiaisonShopArticlesShopReductions;
use App\Models\LiaisonUserShopReduction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;



require_once(app_path().'/fonction.php');



class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * 
     */

     public function updateAddressee(Request $request, $liaisonId)
{
    // Validez les données entrantes
    $validatedData = $request->validate([
        'addressee' => 'required|Integer'
    ]);

    // Récupérer la liaison correspondante
    $liaison = LiaisonShopArticlesBill::find($liaisonId);

    $user = User::find($validatedData['addressee']);

    // Mettre à jour l'adresse de livraison
    $liaison->addressee = $user->lastname . ' ' . $user->name;
    $liaison->id_user = $validatedData['addressee'];
    $liaison->save();

    return response()->json(['message' => 'La désignation a été mise à jour avec succès.']);
}


        public function familySearch($family_id) {
            $familyMembers = User::where('family_id', $family_id)->get();
            return response()->json($familyMembers);
        }


        public function deleteDesignation($id)
        {
            $liaison = LiaisonShopArticlesBill::findOrFail($id);
            
            $article_title = $liaison->designation;
            $bill_id = $liaison->bill_id;
            $user_id = $liaison->id_user;
            
            $liaison->delete();

             // Add message
             ShopMessage::create([
                'message' => 'L\'article <b>' . $article_title . '</b> a été supprimé de la facture.',
                'date' => now(), 
                'id_bill' => $bill_id, 
                'id_customer' => $user_id, 
                'id_admin' => auth()->id(), 
                'state' => 'Public', 
            ]);
            
            $this->recalculateBill($bill_id);
        
           
        
            return response()->json(['message' => 'La ligne a été supprimée avec succès.']);
        }
        

        public function saveSelection(Request $request)
        {
            // Validez les données entrantes
            $validatedData = $request->validate([
                'bill_id' => 'required|exists:bills,id',
                'id_shop_article' => 'required|exists:shop_article,id_shop_article',
                'quantity' => 'required|integer|min:1',
                'recalculate' => 'required|boolean',
                'family_member_id' => 'required|exists:users,user_id'
            ]);
        
            // Récupérer l'article
            $article = Shop_article::find($validatedData['id_shop_article']);
            $user = User::find($validatedData['family_member_id']);
        
            // Créez une nouvelle liaison entre l'article et la facture
            $liaison = new LiaisonShopArticlesBill;
            $liaison->bill_id = $validatedData['bill_id'];
            $liaison->href_product = $article->ref;
            $liaison->ttc = $article->price;
            $liaison->addressee = $user->lastname . ' ' . $user->name;
            $liaison->sub_total = $article->price * $validatedData['quantity'];
            $liaison->designation = $article->title;
            $liaison->id_shop_article = $validatedData['id_shop_article'];
            $liaison->quantity = $validatedData['quantity'];
            $liaison->id_user = $validatedData['family_member_id'];
            $liaison->save();
        
            // Ajout d'un message pour l'article ajouté
            ShopMessage::create([
                'message' => 'L\'article <b>' . $article->title . '</b> a été ajouté à la facture pour : ' . $user->lastname . ' ' . $user->name . '.',
                'date' => now(), 
                'id_bill' => $validatedData['bill_id'], 
                'id_customer' => $user->user_id, 
                'id_admin' => auth()->id(), 
                'state' => 'Public', 
            ]);
        
            // Si 'recalculate' est true, recalculez le montant de la facture
            if ($validatedData['recalculate']) {
                // Recalculer la facture
                $this->recalculateBill($validatedData['bill_id']);
            }
        
            return response()->json(['message' => 'Sélection sauvegardée et facture recalculée avec succès.']);
        }
        
    public function recalculateBill($billId)
    {
        $bill = bills::find($billId);
        $liaisons = LiaisonShopArticlesBill::where('bill_id', $billId)->get();
        $oldTotal = number_format($bill->payment_total_amount, 2, ',', ' ');
        $total = 0;
        foreach ($liaisons as $liaison) {
            $total += $liaison->quantity * $liaison->ttc;
        }
        $bill->payment_total_amount = $total;
        $bill->save();
    
        $newTotal = number_format($total, 2, ',', ' ');
        
        ShopMessage::create([
            'message' => 'Le total de la facture est passé de : <b>' . $oldTotal . ' €</b> à <b>' . $newTotal . ' €</b>.',
            'date' => now(), 
            'id_bill' => $bill->id, 
            'id_customer' => $bill->user_id, 
            'id_admin' => auth()->id(), 
            'state' => 'Public', 
        ]);
    }
    


     
         public function currentSeason()
         {
             $currentSeason = saison_active(); 
             $products = Shop_article::where('saison', $currentSeason)->orderBy('title')->get();
             return response()->json($products);
         }

         public function createBill(Request $request)
     {
         // Validation des données de la requête
         $request->validate([
             'user_id' => 'required|exists:users,user_id',
             'payment_method' => 'required|exists:bills_payment_method,id',
         ]);
         // Création de la facture
         $bill = new bills;
         $bill->date_bill = now();
         $bill->type = 'facture';
         $bill->status = 40;
         $bill->payment_total_amount = 0;
         $bill->ref = 2021;
         $bill->payment_method = $request->payment_method;
         $bill->user_id = $request->user_id;
         $bill->family_id = $request->family_id;
         $bill->total_charges = 0;
         $bill->amount_paid = 0;
         $bill->number = 1;
     
         $bill->save();
     
         // Génération du référence
         $year = date('Y');
         $billIdWithOffset = $bill->id + 10000;
         $bill->ref = "{$year}-{$billIdWithOffset}";
     
         $bill->save();  // sauvegarder à nouveau la facture après la génération de la référence
     
         // Retourner une réponse
         return response()->json([
             'status' => 'success',
             'message' => 'Facture créée avec succès',
         ]);
     }
     

     public function index(Request $request)
     {
         if ($request->has('statusOldBills')) {
             $bill = DB::table('old_bills')
             ->join('users', 'old_bills.user_id', '=', 'users.user_id')
             ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
             ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
             ->select('old_bills.*', 'old_bills.status as bill_status', 'bills_payment_method.icon', 'users.name', 'users.lastname', 'bills_payment_method.payment_method', 'bills_payment_method.image', 'bills_status.status', 'bills_status.image_status', 'bills_status.row_color')
             ->orderBy('old_bills.date_bill', 'desc')
             ->get();
         }else{
             $billQuery = DB::table('bills')
             ->join('users', 'bills.user_id', '=', 'users.user_id')
             ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
             ->join('bills_status', 'bills.status', '=', 'bills_status.id')
             ->select('bills.*', 'bills.status as bill_status', 'bills_payment_method.icon', 'users.name', 'users.lastname', 'bills_payment_method.payment_method', 'bills_payment_method.image', 'bills_status.status', 'bills_status.image_status', 'bills_status.row_color')
             ->orderBy('bills.date_bill', 'desc');
     
             if ($request->has('statusLessThan10')) {
                 $bill = $billQuery->where('bills.status', '<', 10)->get();
             }else {
                 $bill = $billQuery->where('bills.status', '>=', 10)->get();
             }
         }
         $paymentMethods = DB::table('bills_payment_method')->get();
     
         return view('admin.facture', compact('bill', 'paymentMethods'));
     }
     

     public function miseAjourStock()
     {
          MiseAjourStock();
          return response()->json(['message' => 'Le stock a été mis à jour.'], 200);
     }

     public function messageDestroy($id)
{
    
    $message = ShopMessage::find($id);
    if ($message) {
        $message->delete();
        return response()->json(['message' => 'Suppression réussie.']);
    }
    return response()->json(['message' => 'Échec de la suppression.'], 400);
}
     
    public function reduction()
    {
        $shopReductions = ShopReduction::all();
        return view('admin.shop-reduction', compact('shopReductions'));
    }

    public function getReducedPrice($userId, $articleId) {
    $articl = Shop_article::where('id_shop_article', $articleId)->firstOrFail();
    $reducedPrice = getReducedPrice($articleId, $articl->totalprice, $userId);
    $DescReduc = getFirstReductionDescriptionGuest($articleId,$userId);
    return response()->json($reducedPrice);
}

    public function createReduction(Request $request)
    {
        $shopReduction = new ShopReduction();
        $shopReduction->code = $request->code;
        $shopReduction->text = $request->text;
        $shopReduction->percentage = $request->percentage;
        $shopReduction->value = $request->value;
        $shopReduction->description = $request->description;
        $shopReduction->max_per_user = $request->max_per_user;
        $shopReduction->active = $request->active;
        $shopReduction->startvalidity = $request->startvalidity;
        $shopReduction->endvalidity = $request->endvalidity;
        $shopReduction->automatic = $request->automatic;
        $shopReduction->limited_user = $request->limited_user;
        $shopReduction->limited_shop_article = $request->limited_shop_article;
        $shopReduction->save();

        return redirect()->back()->with('success', 'La réduction a été créée avec succès');
    }

    public function deleteReduction(ShopReduction $reduction)
    {


        // Supprimer la réduction
        $reduction->delete();

        // Ajouter un message à la session
        session()->flash('success', 'La réduction a été supprimée avec succès.');

        // Rediriger vers la page d'origine
        return back();
    }


    public function editReduction($id)
{
    $shopReduction = ShopReduction::find($id);

    $liaisons = LiaisonShopArticlesShopReductions::where('id_shop_reduction', $id)->get();
    $shopArticles =  Shop_article::where('saison', '=', saison_active())->orderBy('title')->get();
    $checkedArticles = collect();
    $uncheckedArticles = collect();
    
    foreach ($shopArticles as $shopArticle) {
        $liaison = $liaisons->where('id_shop_article', $shopArticle->id_shop_article)->first();
        if ($liaison) {
            $checkedArticles->push($shopArticle);
        } else {
            $uncheckedArticles->push($shopArticle);
        }
    }

    $liaisonsUser = LiaisonUserShopReduction::where('id_shop_reduction', $id)->get();
    $users = User::select('user_id', 'name', 'lastname')->orderBy('name')->get();
    $checkedUsers = collect();
    $uncheckedUsers= collect();
    foreach ($users as $user) {
        $liaison = $liaisonsUser->where('user_id', $user->user_id)->first();
        if ($liaison) {
            $checkedUsers->push($user);
        } else {
            $uncheckedUsers->push($user);
        }
    }

    return view('admin.edit_reduction')->with([

        'shopReduction' => $shopReduction,
        'checkedArticles' => $checkedArticles,
        'uncheckedArticles' => $uncheckedArticles,
        'checkedUsers' => $checkedUsers,
        'uncheckedUsers' => $uncheckedUsers,
    ]);
}



    public function updateReduction(Request $request, $id)
    {

        $shopReduction = ShopReduction::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'value' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'startvalidity' => 'required|date',
            'endvalidity' => 'required|date|after:startvalidity',
            'usable' => 'required|integer|min:0',
            ], [
            'code.required' => 'Le champ Code est obligatoire.',
            'code.string' => 'Le champ Code doit être une chaîne de caractères.',
            'code.max' => 'Le champ Code ne doit pas dépasser :max caractères.',
            'description.string' => 'Le champ Description doit être une chaîne de caractères.',
            'value.required' => 'Le champ Valeur est obligatoire.',
            'value.numeric' => 'Le champ Valeur doit être un nombre.',
            'value.min' => 'Le champ Valeur doit être supérieur ou égal à :min.',
            'percentage.required' => 'Le champ Pourcentage est obligatoire.',
            'percentage.numeric' => 'Le champ Pourcentage doit être un nombre.',
            'percentage.min' => 'Le champ Pourcentage doit être supérieur ou égal à :min.',
            'percentage.max' => 'Le champ Pourcentage doit être inférieur ou égal à :max.',
            'startvalidity.required' => 'Le champ Date de début de validité est obligatoire.',
            'startvalidity.date' => 'Le champ Date de début de validité doit être une date.',
            'endvalidity.required' => 'Le champ Date de fin de validité est obligatoire.',
            'endvalidity.date' => 'Le champ Date de fin de validité doit être une date.',
            'endvalidity.after' => 'Le champ Date de fin de validité doit être postérieure à la date de début de validité.',
            'usable.required' => 'Le champ Nombre d\'utilisations limité est obligatoire.',
            'usable.integer' => 'Le champ Nombre d\'utilisations limité doit être un entier.',
            'usable.min' => 'Le champ Nombre d\'utilisations limité doit être supérieur ou égal à :min.',
            'automatic.required' => 'Le champ Automatic est obligatoire.',
            ]);
    
        $shopReduction->code = $request->input('code');
        $shopReduction->description = $request->input('description');
        $shopReduction->value = $request->input('value');
        $shopReduction->percentage = $request->input('percentage');
        $shopReduction->startvalidity = $request->input('startvalidity');
        $shopReduction->endvalidity = $request->input('endvalidity');
        $shopReduction->usable = $request->input('usable');
        $shopReduction->state = $request->input('state', 0);
        $shopReduction->automatic = $request->input('automatic', 0);
        
        $shopReduction->save();
    
        return redirect()->back()->with('success', 'Réduction mise à jour avec succès.');
    
    }

    public function updateLiaisons(Request $request)
    {
        $shopReductionId = $request->input('shop_reduction_id');
        $checkedShopArticles = $request->input('shop_article', []);
        $checkedUsers = $request->input('user', []);
        
        // Gérer les liaisons avec les articles
        $existingArticleLiaisons = LiaisonShopArticlesShopReductions::where('id_shop_reduction', $shopReductionId)->get();
        $existingArticleLiaisonIds = $existingArticleLiaisons->pluck('id_shop_article')->toArray();
    
        // créer les nouvelles liaisons d'articles
        foreach ($checkedShopArticles as $shopArticleId) {
            if (!in_array($shopArticleId, $existingArticleLiaisonIds)) {
                // Vérifier si la liaison article-shop existe déjà
                $existingLiaison = LiaisonShopArticlesShopReductions::where('id_shop_reduction', $shopReductionId)
                    ->where('id_shop_article', $shopArticleId)
                    ->first();
    
                if (!$existingLiaison) {
                    $liaison = new LiaisonShopArticlesShopReductions();
                    $liaison->id_shop_reduction = $shopReductionId;
                    $liaison->id_shop_article = $shopArticleId;
                    $liaison->save();
                }
            }
        }
    
        // supprimer les liaisons existantes d'articles qui ne sont plus cochées
        LiaisonShopArticlesShopReductions::where('id_shop_reduction', $shopReductionId)
        ->whereNotIn('id_shop_article', $checkedShopArticles)
        ->delete();
    
        // Gérer les liaisons avec les utilisateurs
        $existingUserLiaisons = LiaisonUserShopReduction::where('id_shop_reduction', $shopReductionId)->get();
        $existingUserLiaisonIds = $existingUserLiaisons->pluck('user_id')->toArray();
    
        // créer les nouvelles liaisons d'utilisateurs
        foreach ($checkedUsers as $userId) {
            if (!in_array($userId, $existingUserLiaisonIds)) {
                // Vérifier si la liaison user-shop existe déjà
                $existingLiaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReductionId)
                    ->where('user_id', $userId)
                    ->first();
    
                if (!$existingLiaison) {
                    $liaison = new LiaisonUserShopReduction();
                    $liaison->id_shop_reduction = $shopReductionId;
                    $liaison->user_id = $userId;
                    $liaison->save();
                }
            }
        }
    
        // supprimer les liaisons existantes d'utilisateurs qui ne sont plus cochées
        LiaisonUserShopReduction::where('id_shop_reduction', $shopReductionId)
        ->whereNotIn('user_id', $checkedUsers)
        ->delete();
    
    
        return redirect()->back()->with('success', 'Les liaisons ont été mises à jour avec succès.');
    }
    
    



    public function paiement_immediat($bill_id)
    {
        // Get the logged-in user's ID
        $user_id = bills::find($bill_id)->user_id;
    
        // Get the family ID from the logged-in user's profile
        $family_id = auth()->user()->family_id;
    
        // Check if the payment record already exists for the bill
        $existing_paiement_immediat = PaiementImmediat::where('bill_id', $bill_id)
                                                ->where('user_id', $user_id)
                                                ->where('family_id', $family_id)
                                                ->first();
    
        // If the payment record already exists, redirect back with an error message
        if ($existing_paiement_immediat) {
            return redirect()->back()->with('error', 'Le paiement immédiat a déjà été effectué pour cette facture');
        }
    
        // If the payment record does not exist, create a new record in the paiement_immediat table
        $paiement_immediat = new PaiementImmediat();
        $paiement_immediat->bill_id = $bill_id;
        $paiement_immediat->user_id = $user_id;
        $paiement_immediat->family_id = $family_id;
        $paiement_immediat->save();

    // Redirect back to the showBill page
    return redirect()->back()->with('success', 'Le paiement immédiat a été intialisé avec succès');
}


public function sendStatusChangedEmail($bill, $oldStatus, $newStatus)
{
    $user = User::find($bill->user_id);
Mail::send('emails.status-changed', ['bill' => $bill, 'oldStatus' => $oldStatus, 'newStatus' => $newStatus], function ($message) use ($user, $bill, $newStatus) {
    $message->from(config('mail.from.address'), config('mail.from.name'));
    $message->to($user->email); 
    $message->subject('Facture ' . $bill->ref . ' :  - '.$newStatus.'');
});

}

public function updateStatus(Request $request, $id)
{
    $bill = bills::find($id);
    $oldStatusId = $bill->status;

    // Fetching old status value from bills_status table
    $oldStatusValue = DB::table('bills_status')->where('id', $oldStatusId)->value('status');

    $bill->status = $request->status;
    $bill->save();

    $bill = DB::table('bills')
        ->leftJoin('bills_status', 'bills.status', '=', 'bills_status.id')
        ->leftJoin('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
        ->leftJoin('users', 'bills.user_id', '=', 'users.user_id')
        ->select('bills.*','bills_status.mail_content', 'bills_status.status as bill_status', 'bills_payment_method.payment_method as bill_payment_method', 'users.name', 'users.lastname')
        ->where('bills.id', $id)
        ->first();

    // Fetching new status value from bills_status table
    $newStatusValue = DB::table('bills_status')->where('id', $bill->status)->value('status');

    if($bill->status == 100){
        $shopMessage = new ShopMessage();
        $shopMessage->message = $bill->bill_status;
        $shopMessage->date = now();
        $shopMessage->id_bill = $bill->id;
        $shopMessage->id_customer = $bill->user_id;
        $shopMessage->id_admin = auth()->user()->user_id;
        $shopMessage->state = 'Privé';
        $shopMessage->somme_payé = $bill->payment_total_amount*-1;
        $shopMessage->save();
    }

    $this->sendStatusChangedEmail($bill, $oldStatusValue, $newStatusValue);
    ShopMessage::create([
        'message' => 'Le Statut de la Facture est passé de : <b>' . $oldStatusValue . '</b> à <b>' . $newStatusValue . '</b>.',
        'date' => now(), 
        'id_bill' => $bill->id, 
        'id_customer' => $bill->user_id, 
        'id_admin' => auth()->id(), 
        'state' => 'Public', 
    ]);

    return  redirect()->back()->with('success', 'Le statut de la facture n°'.$id.' a été modifié avec succès');
}

    public function getOldBills($user_id)
{
    $oldBills = DB::table('old_bills')->join('users', 'old_bills.user_id', '=', 'users.user_id')
        ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
        ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
        ->select('old_bills.*', 'bills_status.image_status', 'bills_payment_method.image', 'old_bills.status as bill_status', 'users.name', 'users.lastname', 'bills_payment_method.payment_method', 'bills_status.status')
        ->where('old_bills.user_id', $user_id)
        ->get();

    if ($oldBills->isEmpty()) {
        return 'Aucune facture trouvée.';
    }

    return view('admin.modals.showOldBills', compact('oldBills'));
}


public function updateDes(Request $request, $id){
    // Récupérer l'ancienne désignation
    $old = LiaisonShopArticlesBill::where('id_liaison', $id)->first();
    $oldDesignation = $old->designation;

    // Mettre à jour la nouvelle désignation
    $article = Shop_article::where('title', $request->designation)->first();
    $old->designation = $article->title;
    $old->href_product = $article->ref;
    $old->ttc = $article->price;
    $old->id_shop_article = $article->id_shop_article;
    $old->save();


    // Send an email
    $user = User::find($request->user_id);
    $oldCourse = $oldDesignation;
    $newCourse = $article->title;
    $liaisonAddress = $old->addressee;
    $billId = $old->bill_id;

    Mail::send('emails.designation-changed', ['oldCourse' => $oldCourse, 'newCourse' => $newCourse, 'liaisonAddress' => $liaisonAddress, 'billId' => $billId, 'user' => $user], function ($message) use ($user) {
        $message->from(config('mail.from.address'), config('mail.from.name'));
        $message->to($user->email);
        $message->subject('La désignation a été modifiée');
    });
    
    // Create a message in ShopMessage
    ShopMessage::create([
        'message' => 'La désignation a été modifiée de <b>' . $oldDesignation . '</b> (' . $liaisonAddress. ') à <b>' . $article->title . '</b> (' . $liaisonAddress. ').',
        'date' => now(), 
        'id_bill' => $old->bill_id, 
        'id_customer' => $request->user_id, 
        'id_admin' => auth()->id(), 
        'state' => 'Public', 
    ]);

    return redirect()->back()->with('success', 'La désignation a été modifiée avec succès');
}



/*
    public function destroy(bills $bill)
    {
        $bill->status = 1;
        $bill->save();
        session()->flash('success', 'Le statut de la facture a été mis à jour avec succès.');
        return back();
    }
    */
    public function destroy(bills $bill)
    {
        if ($bill->status == 1) {
            // Supprimer les liaisons associées à la facture
            DB::table('liaison_shop_articles_bills')
                ->join('bills', 'liaison_shop_articles_bills.bill_id', '=', 'bills.id')
                ->where('bills.id', $bill->id)
                ->delete();
        
            // Supprimer la facture
            $bill->delete();
        
            session()->flash('success', 'La facture et les liaisons associées ont été supprimées avec succès.');
        } else {
            // Modifier le statut de la facture
            $bill->status = 1;
            $bill->save();
        
            session()->flash('success', 'Le statut de la facture a été modifié avec succès.');
        }
    
        return back();
    }
    

    

    
    
    

    public function family($family_id)
    {
        $bill = DB::table('bills')
    ->join('users', 'bills.user_id', '=', 'users.user_id')
    ->join('bills_status', 'bills.status', '=', 'bills_status.id')
    ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
    ->where('bills.family_id', $family_id)
    ->select('bills.*', 'bills_status.image_status as image_status', 'bills_status.row_color as row_color', 'bills_payment_method.image as image', 'users.name', 'users.lastname')
    ->get();

    foreach ($bill as $b) {
        $b->liaisons = DB::table('liaison_shop_articles_bills')
            ->join('users', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
            ->where('bill_id', $b->id)
            ->select('liaison_shop_articles_bills.*', 'users.name as liaison_user_name', 'users.lastname as liaison_user_lastname')
            ->get();
    }
    

        if ($bill->isEmpty()) {
            return 'Aucune facture trouvée.';
        }
        
        return view('admin.modals.factureFamille', compact('bill'))->with('user', auth()->user());
    }

    public function showBill($id)
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
        ->select('id_user','quantity', 'ttc', 'sub_total', 'designation', 'addressee', 'shop_article.image', 'liaison_shop_articles_bills.id_liaison')
        ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('bills.id', '=', $id)
        ->orderBy('designation', 'asc')
        ->get()
        ;

        $status = DB::table('bills_status')
        ->select('id','status')
        ->get();

        $designation = Shop_article::where('saison', '=', saison_active())
        ->orderBy('title', 'asc')
        ->distinct()
        ->pluck('title')
        ->toArray();

        $messages = DB::table('shop_messages')
        ->join('users', 'shop_messages.id_admin', '=', 'users.user_id')
        ->where('shop_messages.id_bill', $id)
        ->select('shop_messages.message', 'shop_messages.id_shop_message', 'shop_messages.date', 'shop_messages.somme_payé', 'users.name', 'users.lastname','shop_messages.id_customer','shop_messages.id_admin','shop_messages.state')
        ->orderBy('shop_messages.date', 'asc')
        ->get();
        $nb_paiment = calculerPaiements($bill->payment_method,$bill->payment_total_amount,$bill->number);

            return view('admin.showBill', compact('bill', 'nb_paiment','shop', 'status', 'designation','messages'))->with('user', auth()->user());
        }

        abort(403, 'Vous n\'êtes pas autorisé à accéder à cette facture.');
     
    }

    public function search(Request $request)
{
    $query = $request->get('query');

    $users = DB::table('users')
                ->where('name', 'like', '%' . $query . '%')
                ->orWhere('lastname', 'like', '%' . $query . '%')
                ->get();

    return response()->json($users);
}


    public function addShopMessage(Request $request, $id) {
        $bill = DB::table('bills')
          ->join('users', 'bills.user_id', '=', 'users.user_id')
          ->where('bills.id', $id)
          ->first();
      
        $shopMessage = new ShopMessage();
        $shopMessage->message = $request->input('comment_content');
        $shopMessage->date = now();
        $shopMessage->id_bill = $bill->id;
        $shopMessage->id_customer = $bill->user_id;
        $shopMessage->id_admin = $request->input('id_admin');
        $shopMessage->state = $request->input('comment_visibility');
        $shopMessage->somme_payé = $request->input('somme_payé');
        $shopMessage->save();

        if ($shopMessage->state == 'Public') {
            $user = User::find($bill->user_id);
            $messageEnvoye = $shopMessage->message;
            $receiverEmail = $user->email;
            $userName = 'Gym Concordia [Bureau]';
            $message = "Votre facture n°{$bill->id} a été créée avec succès.";
            $userEmail = "webmaster@gym-concordia.com";
           // HistoriqueMail($userEmail, $message, $receiverEmail, $userName,$bill, $messageEnvoye);
        }
     
      
        return redirect()->back();
      }
      

}

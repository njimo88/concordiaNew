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
use App\Models\shop_article;
use App\Models\LiaisonShopArticlesBill;
use App\Models\ShopMessage;
use App\Models\PaiementImmediat;
use PDF;
use Intervention\Image\ImageManagerStatic as Image;


require_once(app_path().'/fonction.php');



class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $bill = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->select('bills.*', 'bills.status as bill_status', 'users.name', 'users.lastname', 'bills_payment_method.payment_method', 'bills_payment_method.image', 'bills_status.status', 'bills_status.image_status','bills_status.row_color')
            ->get();

            
        return view('admin.facture')->with('bill', $bill)->with('user', auth()->user());
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


    public function updateStatus(Request $request, $id)
    {
        $bill = bills::find($id);
        $bill->status = $request->status;
        $bill->save();
        return  redirect()->back()->with('success', 'Le statut de la facture n°'.$id.' a été modifié avec succès');
    }
    public function getOldBills($user_id)
    {
        $oldBills = 

            DB::table('old_bills')->join('users', 'old_bills.user_id', '=', 'users.user_id')
        ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
        ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
        ->select('old_bills.*','bills_status.image_status','bills_payment_method.image', 'old_bills.status as bill_status', 'users.name', 'users.lastname', 'bills_payment_method.payment_method', 'bills_status.status')
        ->where('old_bills.user_id', $user_id)
        ->get();

        
        return view('admin.modals.showOldBills', compact('oldBills'));
    }

    public function updateDes(Request $request, $id){

        $new = LiaisonShopArticlesBill::where('id_liaison', $id)->first();
        $article = shop_article::where('title', $request->designation)->first();

        $new->designation = $article->title;
        $new->href_product = $article->ref;
        $new->ttc = $article->totalprice;
        $new->id_shop_article = $article->id_shop_article;
        $new->save();

        return  redirect()->back()->with('success', 'La désignationa été modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bills  $bills
     * @return \Illuminate\Http\Response
     */
    public function delete( $id)
    {
        $bill = bills::find($id);
        $bill->delete();
        return redirect()->route('paiement.facture')->with('success', 'Le facture a été annulé avec succès');

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
        
        return view('admin.modals.factureFamille', compact('bill'))->with('user', auth()->user());
    }

    public function showBill($id)
    {
        updateTotalCharges($id);

        $bill = DB::table('bills')
        ->join('users', 'bills.user_id', '=', 'users.user_id')
        ->join('bills_status', 'bills.status', '=', 'bills_status.id')
        ->where('bills.id', $id)
        ->select('bills.*', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate')
        ->first();

        
        $shop = DB::table('liaison_shop_articles_bills')
        ->select('quantity', 'ttc', 'sub_total', 'designation', 'addressee', 'shop_article.image', 'liaison_shop_articles_bills.id_liaison')
        ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('bills.id', '=', $id)
        ->get();

        $status = DB::table('bills_status')
        ->select('id','status')
        ->get();

        $designation = Shop_article::where('saison', '=', saison_active())
        ->orderBy('title', 'asc')
        ->distinct()
        ->pluck('title')
        ->toArray();

        $messages = DB::table('shop_messages')
        ->join('users', 'shop_messages.id_customer', '=', 'users.user_id')
        ->where('shop_messages.id_bill', $id)
        ->select('shop_messages.message', 'shop_messages.date', 'users.name', 'users.lastname','shop_messages.id_customer','shop_messages.id_admin','shop_messages.state')
        ->orderBy('shop_messages.date', 'asc')
        ->get();
              
        
            return view('admin.showBill', compact('bill', 'shop', 'status', 'designation','messages'))->with('user', auth()->user());
            
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
      
        return redirect()->back();
      }
      

}

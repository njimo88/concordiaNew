<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;
use App\Models\LiaisonShopArticlesBill;
use App\Models\liaison_shop_articles_bills;
use App\Models\ArticlePreparationConfirmation;
use App\Models\DistributionDetail;
use App\Models\ShopMessage;
use App\Models\User;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\Auth;



class LogistiqueController extends Controller
{
    public function preparation()
{
    $saison_active = saison_active(); 

    $products = Shop_article::with([
                        'liaisonShopArticlesBill.bill', 
                        'declinaisons', 
                        'liaisonShopArticlesBill.productReturn',
                        'liaisonShopArticlesBill.productReturn.user'
                    ])
                    ->whereHas('liaisonShopArticlesBill', function($query){
                        $query->whereHas('bill', function($q){
                            $q->where('status', 100);
                        })->where('is_prepared', false)
                        ->where('is_distributed', false);
                    })
                    ->where('type_article', 2)
                    ->where('saison', $saison_active)
                    ->get();
    return view('admin.logistique.preparation', compact('products'));
}


public function distribution()
{
    $saison_active = saison_active();

    $articlesForDistribution = Shop_article::with(['declinaisons', 'liaisonShopArticlesBill.preparationConfirmation.user'])
        ->where('type_article', 2)
        ->where('saison', $saison_active)
        ->whereHas('liaisonShopArticlesBill', function ($query) {
            $query->where('is_prepared', true)
                ->where('is_distributed', false)
                ->whereHas('bill', function ($query) {
                    $query->where('status', 100);
                });
        })
        ->get();

    $articlesForDistribution->map(function ($article) {
        $article->declinaison_info = $article->declinaisons->first();
        $article->liaisonShopArticlesBill = $article->liaisonShopArticlesBill->filter(function ($liaison) {
            return $liaison->is_prepared && !$liaison->is_distributed && optional($liaison->bill)->status == 100;
        })->values();
        if ($liaison = $article->liaisonShopArticlesBill->first()) {
            $article->preparation_details = $liaison->preparationConfirmation;
            $article->prepared_by_name = optional($liaison->preparationConfirmation->user)->lastname . ' ' . optional($liaison->preparationConfirmation->user)->name;
            $article->prepared_at = optional($liaison->preparationConfirmation)->confirmed_at->format('d/m/Y à H:i');
        }
        return $article;
    });

    $currentSeason = saison_active();
    // Articles distribués
    $articlesDistributed = LiaisonShopArticlesBill::with([
        'shopArticle' => function ($query) use ($currentSeason) {
            $query->where('type_article', 2)
                  ->where('saison', $currentSeason)
                  ->with('declinaisons');
        }, 
        'bill' => function ($query) {
            $query->where('status', 100);
        }, 
        'preparationConfirmation', 
        'distributionDetail'
    ])
    ->where('is_prepared', true)
    ->where('is_distributed', true)
    ->whereHas('distributionDetail')
    ->whereHas('preparationConfirmation')
    ->join('distribution_details', 'distribution_details.liaison_shop_article_bill_id', '=', 'liaison_shop_articles_bills.id_liaison')
    ->orderBy('distribution_details.distributed_at', 'desc')
    ->take(50)
    ->select('liaison_shop_articles_bills.*') 
    ->get()
    ->map(function ($item) {
        if ($item->shopArticle && $item->shopArticle->declinaisons->isNotEmpty()) {
            $item->declinaisonName = $item->shopArticle->declinaisons->first()->libelle;
        }
        return $item;
    });
    return view('admin.logistique.distribution', compact('articlesForDistribution', 'articlesDistributed'));
}


public function confirmPreparation(Request $request)
{
    $liaisonId = $request->input('liaisonId');
    $liaison = LiaisonShopArticlesBill::find($liaisonId);
    if ($liaison && !$liaison->is_prepared) {
        $liaison->is_prepared = true;
        $liaison->save();
        
        ArticlePreparationConfirmation::create([
            'liaison_shop_article_bill_id' => $liaisonId,
            'confirmed_by_user_id' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        $article_title = $liaison->shopArticle->title; 
        $bill_id = $liaison->bill_id;
        $user_id = $liaison->id_user;

        ShopMessage::create([
            'message' => 'L\'article <b>' . $article_title . '</b> a été préparé pour la facture.',
            'date' => now(),
            'id_bill' => $bill_id,
            'id_customer' => $user_id,
            'id_admin' => auth()->id(),
            'state' => 'Public', 
        ]);

        return response()->json(['success' => 'La préparation a été confirmée et un message a été créé.']);
    }

    return response()->json(['error' => 'Action non autorisée.'], 403);
}


public function nonConcerne(Request $request)
{
    $liaisonId = $request->input('liaisonId');
    $liaison = LiaisonShopArticlesBill::find($liaisonId);
    if ($liaison && !$liaison->is_prepared) {
        $liaison->is_prepared = true;
        $liaison->is_distributed = true;
        $liaison->save();
        return response()->json(['success' => 'La préparation a été confirmée.']);
    }

    return response()->json(['error' => 'Action non autorisée.'], 403);
}

public function confirmDistribution(Request $request)
{
    $liaisonId = $request->input('liaisonId');
    $liaison = LiaisonShopArticlesBill::find($liaisonId);
    if ($liaison && !$liaison->is_distributed) {
        $liaison->is_distributed = true;
        $liaison->save();
        DistributionDetail::create([
            'liaison_shop_article_bill_id' => $liaisonId,
            'distributed_by_user_id' => auth()->id(),
            'distributed_at' => now(),
        ]);

        $article_title = $liaison->shopArticle->title; 
        $bill_id = $liaison->bill_id;
        $user_id = $liaison->id_user;

        ShopMessage::create([
            'message' => 'L\'article <b>' . $article_title . '</b> a été distribué.',
            'date' => now(),
            'id_bill' => $bill_id,
            'id_customer' => $user_id,
            'id_admin' => auth()->id(),
            'state' => 'Public', 
        ]);

        return response()->json(['success' => 'La distribution a été confirmée et un message a été créé.']);
    }

    return response()->json(['error' => 'Action non autorisée.'], 403);
}


public function getUserLiaisons(Request $request)
{
    $userId = $request->userId;
    $currentSeason = saison_active(); 

    $userFamilyId = User::find($userId)->family_id ?? null;

    $liaisons = LiaisonShopArticlesBill::with('shopArticle')
                ->whereHas('shopArticle', function($query) use ($currentSeason) {
                    $query->where('saison', $currentSeason)
                          ->where('type_article', 1);
                })
                ->whereHas('bill', function($query) use ($userFamilyId) {
                    $query->where('status', '>', 9)
                          ->where(function($query) use ($userFamilyId) {
                              $query->where('user_id', Auth::id()) 
                                    ->orWhere('family_id', $userFamilyId); 
                          });
                })
                ->where(function($query) use ($userId, $userFamilyId) {
                    $query->where('id_user', $userId); 
                    if ($userFamilyId) {
                        $query->orWhereHas('bill', function($query) use ($userFamilyId) {
                            $query->where('family_id', $userFamilyId); 
                        });
                    }
                })
                ->get();

    return view('admin.logistique.preparationModal', compact('liaisons'));
}

public function returnProduct(Request $request)
{
    $liaisonId = $request->input('liaisonId'); 
    $reason = $request->input('reason'); 

    $liaison = LiaisonShopArticlesBill::with('distributionDetail')->find($liaisonId);
    if ($liaison && $liaison->is_distributed) {
        $productReturn = ProductReturn::create([
            'liaison_shop_article_bill_id' => $liaisonId,
            'returned_by_user_id' => auth()->id(),
            'reason' => $reason,
            'returned_at' => now()
        ]);
        $liaison->is_distributed = false;
        $liaison->is_prepared = false;
        $liaison->is_returned = true;
        $liaison->save();

        return back()->with('success', 'Le produit a été marqué pour retour.');
    }

    return back()->with('error', 'Impossible de traiter le retour pour le produit sélectionné.');
}

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;
use App\Models\LiaisonShopArticlesBill;
use App\Models\ArticlePreparationConfirmation;


class LogistiqueController extends Controller
{
    public function preparation()
{


    $saison_active = saison_active(); 

        $products = Shop_article::with(['liaisonShopArticlesBill.bill', 'declinaisons'])
                            ->whereHas('liaisonShopArticlesBill', function($query){
                                $query->whereHas('bill', function($q){
                                    $q->where('status', 100);
                                })->where('is_prepared', false);
                            })
                            ->where('type_article', 2)
                            ->where('saison', $saison_active)
                            ->get();
    return view('admin.logistique.preparation', compact('products'));
}

public function distribution()
{
    return view('admin.logistique.distribution');
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

        return response()->json(['success' => 'La préparation a été confirmée.']);
    }

    return response()->json(['error' => 'Action non autorisée.'], 403);
}

public function nonConcerne(Request $request)
{
    $liaisonId = $request->input('liaisonId');
    $liaison = LiaisonShopArticlesBill::find($liaisonId);
    if ($liaison && !$liaison->is_prepared) {
        $liaison->is_prepared = true;
        $liaison->save();
        return response()->json(['success' => 'La préparation a été confirmée.']);
    }

    return response()->json(['error' => 'Action non autorisée.'], 403);
}


}

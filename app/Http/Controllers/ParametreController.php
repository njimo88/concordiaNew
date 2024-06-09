<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Parametre;
use App\Models\Shop_article;
use App\Models\shop_article_0;
use App\Models\shop_article_1;
use App\Models\shop_article_2;


class ParametreController extends Controller
{
    public function index()
{
    $seasons = Parametre::all()->sortByDesc('saison');  
    return view('admin.parametres', ['seasons' => $seasons]);
}

public function setActiveSeason(Request $request)
{
    Parametre::query()->update(['activate' => 0]);
    Parametre::where('saison', $request->activeSeason)->update(['activate' => 1]);
    return redirect('/parametres')->with('success', 'La saison active a été mise à jour avec succès.');
}

public function createNewSeason() {
    $lastSeason = Parametre::orderBy('saison', 'desc')->first();

    if($lastSeason) {
        $newSeason = $lastSeason->replicate();
        $newSeason->saison = $newSeason->saison + 1;
        $newSeason->activate = 0;
        $newSeason->save();
        return redirect()->back()->with('success', 'La nouvelle saison a été créée avec succès.');
    } else {
        return redirect()->back()->with('error', 'Erreur lors de la création de la nouvelle saison.');
    }
}

public function update(Request $request, $id)
{
    $season = Parametre::where('saison', $id)->first();
    
    $season->fichier_inscription1 = $request->fichier_inscription1;
    $season->fichier_inscription2 = $request->fichier_inscription2;

    $season->save();

    return redirect()->back()->with('success', 'La saison a été mise à jour avec succès.');
}

public function duplicateProducts(Request $request, $season)
{
    $notDuplicated = [];
    $sourceSeason = $season;
    $targetSeason = $request->input('target_season');
    $startValidity = $request->input('start_validity');
    $startYear = date('Y', strtotime($startValidity));
    $endYear = $startYear + 1;
    $endValidity = $endYear . '-05-31';

    $sourceArticles = Shop_article::where('saison', $sourceSeason)->get();
    
    foreach ($sourceArticles as $article) {
        $newArticle = $article->replicate();
        $newArticle->saison = $targetSeason;
        $newArticle->title = "0-" . $newArticle->title;
        $newArticle->ref = substr($newArticle->ref, 0, -5) . substr($targetSeason, -2) . "-" . ((int)substr($targetSeason, -2) + 1);
        $newArticle->startvalidity = $startValidity;
        $newArticle->endvalidity = $endValidity;
        $newArticle->need_member = 0;
        $newArticle->stock_actuel = $newArticle->stock_ini;
        $newArticle->push();
        $newId = DB::table('shop_article')->max('id_shop_article');

        if ($article->type_article == 1) {
            $sourceArticle1 = shop_article_1::where('id_shop_article', $article->id_shop_article)->first();
            if ($sourceArticle1) {
                $newArticle1Data = $sourceArticle1->attributesToArray();
                $newArticle1Data['id_shop_article'] = $newId;
                $newArticle1Data['lesson'] = str_replace($sourceSeason, $targetSeason, $newArticle1Data['lesson']);
                $newArticle1Data['updated_at'] = Carbon::now();
                $newArticle1Data['created_at'] = Carbon::now();
                DB::table('shop_article_1')->insert($newArticle1Data);
            }else{
                $notDuplicated[] = $article;
            }
        }
        if ($article->type_article == 0) {
            $sourceArticle0 = shop_article_0::where('id_shop_article', $article->id_shop_article)->first();
            if ($sourceArticle0) {
                $newArticle0Data = $sourceArticle0->attributesToArray();
                $newArticle0Data['id_shop_article'] = $newId;
                $newArticle0Data['updated_at'] = Carbon::now();
                $newArticle0Data['created_at'] = Carbon::now();
                DB::table('shop_article_0')->insert($newArticle0Data);
            }else{
                $notDuplicated[] = $article;
            }
        } elseif ($article->type_article == 2) {
            $sourceArticle2 = shop_article_2::where('id_shop_article', $article->id_shop_article)->first();
            if ($sourceArticle2) {
                $newArticle2Data = $sourceArticle2->attributesToArray();
                $newArticle2Data['id_shop_article'] = $newId;
                $newArticle2Data['updated_at'] = Carbon::now();
                $newArticle2Data['created_at'] = Carbon::now();
                DB::table('shop_article_2')->insert($newArticle2Data);
            }else{
                $notDuplicated[] = $article;
            }
        }
        
    }
    
    foreach ($notDuplicated as $article) {
        echo "Article ID: " . $article->id_shop_article . " n'a pas pu être dupliqué.\n";
    }

    return redirect()->back()->with('success', 'Les produits ont été dupliqués avec succès');
}

public function upgradeArticles(Request $request)
{
    $sourceSeason = $request->input('source_season');
    $targetSeason = $request->input('target_season');
    $startValidity = $request->input('start_validity');
    $endValidity = $request->input('end_validity');

    $yearDifference = $targetSeason - $sourceSeason;

    // Calcul du nombre total de semaines à ajouter en tenant compte des années bissextiles
    $weeksToAdd = $yearDifference * 52;

    for ($year = $sourceSeason; $year < $targetSeason; $year++) {
        if ($this->isLeapYear($year)) {
            $weeksToAdd += 1;
        }
    }

    $sourceArticles = Shop_article::where('saison', $sourceSeason)->where('type_article', 1)->get();

    foreach ($sourceArticles as $article) {
        $newArticle = $article->replicate();
        $newArticle->title = "0_" . $newArticle->title;
        $newArticle->ref = "0_" . substr($newArticle->ref, 0, -5) . substr($targetSeason, -2) . "-" . ((int)substr($targetSeason, -2) + 1);
        $newArticle->saison = $targetSeason;
        $newArticle->startvalidity = $startValidity;
        $newArticle->endvalidity = $endValidity;

        $newArticle->save();

        $newId = $newArticle->id_shop_article;
        $sourceArticle1 = shop_article_1::where('id_shop_article', $article->id_shop_article)->first();

        if ($sourceArticle1) {
            $newArticle1Data = $sourceArticle1->attributesToArray();
            $newArticle1Data['id_shop_article'] = $newId;
            $newArticle1Data['stock_actuel'] = $newArticle1Data['stock_ini'];

            $lessonData = json_decode($newArticle1Data['lesson'], true);
            foreach ($lessonData['start_date'] as &$date) {
                $date = date('Y-m-d H:i', strtotime($date . " + $weeksToAdd weeks"));
            }
            foreach ($lessonData['end_date'] as &$date) {
                $date = date('Y-m-d H:i', strtotime($date . " + $weeksToAdd weeks"));
            }
            $newArticle1Data['lesson'] = json_encode($lessonData);
            $newArticle1Data['updated_at'] = Carbon::now();
            $newArticle1Data['created_at'] = Carbon::now();
            DB::table('shop_article_1')->insert($newArticle1Data);
        }
    }

    return redirect()->back()->with('success', 'Les articles ont été mis à jour avec succès');
}

// Méthode pour vérifier si une année est bissextile
private function isLeapYear($year)
{
    return ($year % 4 == 0) && (($year % 100 != 0) || ($year % 400 == 0));
}




}

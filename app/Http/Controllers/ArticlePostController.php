<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;
use App\Models\Parametre;
use Carbon\Carbon;

class ArticlePostController extends Controller
{
    public function index(Request $request)
    {
        $saisons = Parametre::select('saison')->distinct()->orderBy('saison', 'desc')->get();
        $saison_active = $request->input('saison', $saisons->first()->saison);
        $showOldArticles=false;
        $now = Carbon::now();
        $articles = Shop_article::where('saison', $saison_active)
            ->where('startvalidity', '<=', $now)
            ->where('endvalidity', '>=', $now)
            ->orderBy('ref', 'asc')
            ->get();

        return view('postArticle/indexArticle', [
            'articles' => $articles,
            'saison_active' => $saison_active,
            'saisons' => $saisons,
            'oldArticles'=>$showOldArticles
        ]);
    }

public function fetchArticles(Request $request)
{
    $saison_active = $request->input('saison');
    
    $expire = $request->input('expire');
    
    if($expire==null)
    {
        $showOldArticles = false;
    }else{ $showOldArticles = true; }


    $now = Carbon::now();

    // Initialiser la requête de base
    $articlesQuery = Shop_article::where('saison', $saison_active);

    // Si on demande les anciens articles
if ($showOldArticles) {
    $articlesQuery->where('endvalidity', '<', $now); // Articles expirés
} else {
    $articlesQuery->where('startvalidity', '<=', $now)
                  ->where('endvalidity', '>=', $now); // Articles valides
}
    

    // Récupérer les articles avec le bon filtre
    $saisons = Parametre::select('saison')->distinct()->orderBy('saison', 'desc')->get();
    $articles = $articlesQuery->orderBy('ref', 'asc')->get();
    return view('postArticle/indexArticle', [
        'articles' => $articles,
        'saison_active' => $saison_active,
        'saisons' => $saisons,
        'oldArticles'=>$showOldArticles
    ]);
    //return response()->json($articles);
}




}

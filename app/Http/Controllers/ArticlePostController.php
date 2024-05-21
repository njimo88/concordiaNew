<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;
use App\Models\Parametre;

class ArticlePostController extends Controller
{
    public function index(Request $request)
    {
        $saisons = Parametre::select('saison')->distinct()->orderBy('saison', 'desc')->get();

        $saison_active = $request->input('saison', $saisons->first()->saison);

        $articles = Shop_article::where('saison', $saison_active)->orderBy('ref', 'asc')->get();

        return view('postArticle/indexArticle', [
            'articles' => $articles,
            'saison_active' => $saison_active,
            'saisons' => $saisons,
        ]);
    }

    public function fetchArticles(Request $request)
    {
        $saison_active = $request->input('saison');

        $articles = Shop_article::where('saison', $saison_active)->orderBy('ref', 'asc')->get();

        return response()->json($articles);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\A_Blog_Post;
use App\Models\Shop_article;
use Illuminate\Support\Facades\Auth;
require_once(app_path().'/fonction.php');

class SearchController extends Controller
{
    public function searchBlog(Request $request)
    {
        $query = $request->input('query');
        $results = A_Blog_Post::where('titre', 'like', '%' . $query . '%')->get();
        return response()->json($results);
    }

    public function searchShop(Request $request)
    {
        $query = $request->input('query');
        if (Auth::check() && Auth::user()->role >= 90) {
            $results = Shop_article::where('title', 'like', '%' . $query . '%')->get();
        } else {
            $activeSaison = saison_active();
            $results = Shop_article::where('title', 'like', '%' . $query . '%')->where('saison', $activeSaison)->get();
        }
        return response()->json($results);
    }

    public function searchResults(Request $request)
    {
        $searchType = $request->input('type');
        $searchQuery = $request->input('query');

        $results = [];
        if ($searchType === 'blog') {
            $results = A_Blog_Post::where('titre', 'like', '%' . $searchQuery . '%')->get();
        } else if ($searchType === 'shop') {
            $results = Shop_article::where('title', 'like', '%' . $searchQuery . '%')->get();
        } else {
            // Invalid search type, return an error
        }

        return view('search-results', ['results' => $results, 'query' => $searchQuery, 'searchType' => $searchType]);
    }
}


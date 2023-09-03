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
        $results = A_Blog_Post::where('titre', 'like', '%' . $query . '%')
                                ->orderBy('date_post', 'desc')  
                                ->get();
        return response()->json($results);
    }

    public function searchShop(Request $request)
    {
        $query = $request->input('query');
        if (Auth::check() && Auth::user()->role >= 90) {
            $results = Shop_article::where('title', 'like', '%' . $query . '%')
                                    ->orderBy('saison', 'desc')  
                                    ->get();
        } else {
            $activeSaison = saison_active();
            $results = Shop_article::where('title', 'like', '%' . $query . '%')
                                    ->where('saison', $activeSaison)
                                    ->orderBy('saison', 'desc')  
                                    ->get();
        }
        return response()->json($results);
    }


    public function searchResults(Request $request)
    {
        $searchType = $request->input('type');
        $searchQuery = $request->input('query');

        $results = [];
        if ($searchType === 'blog') {
            $results = A_Blog_Post::where('titre', 'like', '%' . $searchQuery . '%')
                                    ->orderBy('date_post', 'desc')  
                                    ->get();
        } else if ($searchType === 'shop') {
            if (Auth::check() && Auth::user()->role >= 90) {
                $results = Shop_article::where('title', 'like', '%' . $searchQuery . '%')
                                        ->orderBy('saison', 'desc')  
                                        ->get();
            } else {
                $activeSaison = saison_active();
                $results = Shop_article::where('title', 'like', '%' . $searchQuery . '%')->where('saison', $activeSaison)
                                        ->orderBy('saison', 'desc')  
                                        ->get();
            }
        } else {
            // Invalid search type, return an error

        }

        return view('search-results', ['results' => $results, 'query' => $searchQuery, 'searchType' => $searchType]);
    }
}


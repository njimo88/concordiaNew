<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use App\Models\Visitors;
use App\Models\statistiques_visites;

class VisitorCounter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

                // Get the current year
            $currentYear = date('Y');

            // Retrieve the row that represents the homepage for the current year
            $visitorCount = statistiques_visites::where('page', '=', '/')
                                                ->where('annee', '=', $currentYear)
                                                ->first();


          if($visitorCount){
              $visitorCount->nbre_visitors = $visitorCount->nbre_visitors + 1;
              $visitorCount->save();

          }else{

                    // If no row is found for the current year, create a new row
                statistiques_visites::create([
                    'page' => '/',
                    'nbre_visitors' => 1,
                    'annee' => $currentYear
                ]);

       
          }
  
          return $next($request);
      
        
    }




}

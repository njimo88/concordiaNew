<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use App\Models\statistiques_visites;

class PageCounterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    
              /*
                $sessionKey = 'page_counter';
                $currentMonth = date('n'); // get the current month (1-12)

                if (session()->get('reset_month') != $currentMonth) {
                    session()->put($sessionKey, 0);
                    session()->put('reset_month', $currentMonth);
                }
                
                if ($request->session()->has($sessionKey)) {
                    $count = $request->session()->get($sessionKey);
                    $request->session()->put($sessionKey, $count + 1);
                } else {
                    $request->session()->put($sessionKey, 1);
                    session()->put('reset_month', $currentMonth);
                }
            
                return $next($request);

------------------------------------- reset chaque annee -------------

                            // Get the current year and month
                            $currentYear = date('Y');
               

                            // Check if a row exists for the current page and year
                            $existingRow = statistiques_visites::where('page', $currentPath)
                                ->where('annee', $currentYear)
                                ->first();



                                if ($existingRow) {
                                    // If a row exists, update the existing row
                                    $existingRow->nbre_visitors += $count;
                                    $existingRow->save();
                                } else {
                                    // If a row does not exist, create a new row with the current year and month
                                    statistiques_visites::create([
                                        'page' => $currentPath,
                                        'nbre_visitors' => $count,
                                        'annee' => $currentYear,
                                       
                                    ]);
                                }

                                // Check if the year has changed
                            $previousYear = Session::get('previous_year');
                            if ($previousYear !== $currentYear) {
                            
                                $previousPageCountArray = Session::get('previous_page_count_array', []);
                                $previousVisitorCount = Session::get('previous_visitor_count', 0);
                                // Save the previous year's page count array and visitor count to a file or database
                                // ...
                                // Clear the page count array and visitor count for the new year
                                Session::forget('page_count_array');
                                Session::forget('visitor_count');
                                // Update the "previous_year" session variable
                                Session::put('previous_year', $currentYear);
                                // Save the current page count and visitor count as the first entry for the new year
                                statistiques_visites::create([
                                    'page' => $currentPath,
                                    'nbre_visitors' => $count,
                                    'annee' => $currentYear,
                                ]) ;

                            }

          
            */public function handle($request, Closure $next)

                 {

                        // Get the page count array from the session
                    $pageCountArray = Session::get('page_count_array', []);

                        // Get the visitor count from the session
                    $count = Session::get('visitor_count', 0);
                    
                    
                        // Get the current page URL
                    $currentPage = $request->url();
                                    // Get the current page path
                    $currentPath = $request->path();

                    // Increment the page count or add a new key-value pair
                    if (array_key_exists($currentPath, $pageCountArray)) {
                        $pageCountArray[$currentPath]++;
                    } else {
                        $pageCountArray[$currentPath] = 1;
                    }

                      
                      // Increment the visitor count
                    $count++;

                      
                        // Store the updated page count array in the session
                     Session::put('page_count_array', $pageCountArray);

                
                    // Store the updated visitor count in the session
                    Session::put('visitor_count', $count);


                     
                   /* 
                    // Save the result to the database
                      $statistiqueVisite = new statistiques_visites();
                      $statistiqueVisite->page = $currentPath;
                      $statistiqueVisite->nbre_visitors = $count;
                      $statistiqueVisite->annee = date('Y');
                      $statistiqueVisite->save();

                                            another way 

                    // Save the result to the database

                                            $statistiqueVisite = statistiques_visites::updateOrCreate(
                                                ['page' => $currentPath],
                                                ['nbre_visitors' => $count, 'annee' => date('Y')]
                                            );
                      

                      */
                        // Check if there is an existing row for the current year and page
                                $statistiqueVisite = statistiques_visites::where('page', $currentPath)
                                ->where('annee', date('Y'))
                                ->first();

                            if ($statistiqueVisite) {
                                // If there is an existing row for the current year and page, update the visitor count
                                $statistiqueVisite->nbre_visitors += 1;
                                $statistiqueVisite->save();
                            } else {
                                // If there is no existing row for the current year and page, create a new row
                                statistiques_visites::create([
                                    'page' => $currentPath,
                                    'nbre_visitors' => 1,
                                    'annee' => date('Y'),
                                ]);
                            }












                                            /* Update the visitor count in the database

                                                        $statistiqueVisite = statistiques_visites::where('page', $currentPath)
                                                        ->where('annee', date('Y'))
                                                        ->increment('nbre_visitors', 1);

                                                        */

                                            // If the page is being visited for the first time this month, create a new entry in the database
                                                      
                                                          
                                               // dd( $pageCountArray);
                                                    
                                                    // Continue with the request
                                              return $next($request);


        }

    }

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PageCounterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
            {
              
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
            }

}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
require_once(app_path().'/fonction.php');


class CheckFirstLoginOfDay
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
        $today = Carbon::now()->format('Y-m-d');
        $cacheKey = "first_login_of_day_{$today}";

        if (!Cache::get($cacheKey)) {

            printUsersBirthdayOnImage();
            
            Cache::put($cacheKey, true, Carbon::tomorrow()->diffInSeconds(Carbon::now()));
        }

        return $next($request);
    }

 
}

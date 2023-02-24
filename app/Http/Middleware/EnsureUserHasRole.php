<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, String $role)
    {
        $user = $request->user();

        if (!$user) {
            return redirect('login');
        }
    
        // Check if user has a role greater than 90
        if ($user->role >= $role) {
            return $next($request);
        }
    
        return abort(403);
    }
}

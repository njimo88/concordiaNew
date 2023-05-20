<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getName() !== 'verify_password') {
            if (DB::table('system')->where('name', 'maintenance')->value('value') == 1) {
                $maintenanceMode = true;
                $allowedIps = DB::table('maintenances')->pluck('ip_address')->toArray();

                if (in_array($request->ip(), $allowedIps)) {
                    $maintenanceMode = false;
                }

                if ($maintenanceMode) {
                    return response()
                        ->view('maintenance')
                        ->header('Retry-After', 60);
                }
            }
        }

        return $next($request);
    }
}


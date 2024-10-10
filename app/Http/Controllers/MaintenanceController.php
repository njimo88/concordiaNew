<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Maintenance; 

class MaintenanceController extends Controller
{
    public function verifyPassword(Request $request)
    {
        $password = DB::table('system')->where('name', 'password_maintenance')->first()->Message;
        if ($request->password === $password) {
            Maintenance::firstOrCreate(['ip_address' => $request->ip()]);
            return redirect()->back()->with('success', 'Accès accordé');
        }
        return redirect()->back()->with('error', 'Mot de passe incorrect');
    }
}



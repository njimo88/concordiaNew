<?php

namespace App\Http\Controllers;

use App\Jobs\ClickAsso;
use Illuminate\Support\Facades\Http;

class ClickAssoController extends Controller
{
    private function getLogin()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://application.clickasso.fr/api/Common/Login', [
            'Association' => 'gym-concordia',
            'Login' => 'concordia-admin',
            'Password' => '#ClickAsso4Concordia',
            'RememberMe' => false
        ]);

        $response = json_decode($response);

        return $response->Cookie ?? null;
    }

    public function index()
    {
        $cookie = $this->getLogin();

        return view('admin.clickAsso', ['cookie' => $cookie]);
    }

    public function triggerJob()
    {
        ClickAsso::dispatchNow();
        return response()->json(['status' => 'Job dispatched']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class ServerBrowserController extends Controller
{
    public function index()
    {
        return view('admin.server_browser');
    }
}

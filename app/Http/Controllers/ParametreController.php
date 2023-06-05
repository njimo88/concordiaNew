<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Parametre;

class ParametreController extends Controller
{
    public function index()
{
    $seasons = Parametre::all()->sortByDesc('saison');  
    return view('admin.parametres', ['seasons' => $seasons]);
}

public function setActiveSeason(Request $request)
{
    Parametre::query()->update(['activate' => 0]);
    Parametre::where('saison', $request->activeSeason)->update(['activate' => 1]);
    return redirect('/parametres')->with('success', 'La saison active a été mise à jour avec succès.');
}


}

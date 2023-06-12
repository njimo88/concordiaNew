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

public function createNewSeason() {
    $lastSeason = Parametre::orderBy('saison', 'desc')->first();

    if($lastSeason) {
        $newSeason = $lastSeason->replicate();
        $newSeason->saison = $newSeason->saison + 1;
        $newSeason->activate = 0;
        $newSeason->save();
        return redirect()->back()->with('success', 'La nouvelle saison a été créée avec succès.');
    } else {
        return redirect()->back()->with('error', 'Erreur lors de la création de la nouvelle saison.');
    }
}

public function update(Request $request, $id)
{
    $season = Parametre::where('saison', $id)->first();
    
    $season->fichier_inscription1 = $request->fichier_inscription1;
    $season->fichier_inscription2 = $request->fichier_inscription2;

    $season->save();

    return redirect()->back()->with('success', 'La saison a été mise à jour avec succès.');
}



}

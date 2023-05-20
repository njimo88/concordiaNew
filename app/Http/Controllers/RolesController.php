<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role; 
use App\Models\Room;

class RolesController extends Controller
{
    //




  public function indexRoles(){


        $roles = DB::table('roles')->select('*')->get();

       return view('roles/roles_index',compact('roles'))->with('user', auth()->user());


    }

    public function index_salle(){
            
        $rooms = Room::orderBy('name', 'asc')->get();
        return view('admin/roomsIndex', ['rooms' => $rooms]);

    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->back()->with('success', 'Salle supprimée avec succès');
    }

    // Edit Room
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin/roomsEdit', ['room' => $room]);
    }

    // Update Room
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $room->update($request->all());
        return redirect()->route('index_salle')->with('success', 'Salle modifiée avec succès');
    }

    public function modif_les_roles($id, Request $request){

        $role = Role::find($id);
        $role->name=  $request->input('name');
        $role->estAutoriserDeVoirMembres =  $request->input('estAutoriserDeVoirMembres');
        $role->estAutoriserDeVoirClickAsso =  $request->input('estAutoriserDeVoirClickAsso');
        $role->estAutoriserDeRedigerArticle =  $request->input('estAutoriserDeRedigerArticle');
        $role->estAutoriserDeGererSlider=  $request->input('estAutoriserDeGererSlider');
        $role->estAutoriserDeVoirCategories=  $request->input('estAutoriserDeVoirCategories');
        $role->estAutoriserDeVoirFacture=  $request->input('estAutoriserDeVoirFacture');
        $role->estAutoriserDeVoirReduction = $request->input('estAutoriserDeVoirReduction');
        $role->estAutoriserDeVoirArticleBoutique = $request->input('estAutoriserDeVoirArticleBoutique');
        $role->estAutoriserDeVoirCategorieBoutique = $request->input('estAutoriserDeVoirCategorieBoutique');
        $role->estAutoriserDeVoirMessages = $request->input('estAutoriserDeVoirMessages');
        $role->estAutoriserDeVoirHistorique = $request->input(' estAutoriserDeVoirHistorique');
        $role->estAutoriserDeVoirCours = $request->input('estAutoriserDeVoirCours');
        $role->estAutoriserDeVoirAnimations = $request->input('estAutoriserDeVoirAnimations');
        $role->estAutoriserDeVoirStatsExports = $request->input('estAutoriserDeVoirStatsExports');

        $role->estAutoriserDeVoirValiderCertificats = $request->input('estAutoriserDeVoirValiderCertificats');
        $role->estAutoriserDeVoirGestionProfessionnels = $request->input('estAutoriserDeVoirGestionProfessionnels');
        $role->estAutoriserDeVoirCalculDesSalaires = $request->input('estAutoriserDeVoirCalculDesSalaires');
        $role->estAutoriserDeVoirValiderLesHeures = $request->input('estAutoriserDeVoirValiderLesHeures');
        $role->estAutoriserDeVoirGestionDesDroits = $request->input('estAutoriserDeVoirGestionDesDroits');
      
        $role->estAutoriserDeVoirSalles = $request->input('estAutoriserDeVoirSalles');
        $role->estAutoriserDeVoirParametresGeneraux = $request->input('estAutoriserDeVoirParametresGeneraux');
        $role->estAutoriserDeVoirMessageGeneral = $request->input('estAutoriserDeVoirMessageGeneral');
        $role->supprimer_edit_ajout_user = $request->input('supprimer_edit_ajout_user');
        $role->reinitialiser_mot_de_passe_user = $request->input('reinitialiser_mot_de_passe_user');

        $role->supprimer_edit_facture = $request->input('supprimer_edit_facture');
        $role->paiement_immediat = $request->input('paiement_immediat');
        $role->changer_status_facture = $request->input('changer_status_facture');
        $role->changer_designation_facture = $request->input('changer_designation_facture');
        $role->supprimer_edit_ajout_categorie = $request->input('supprimer_edit_ajout_categorie');

        $role->supprimer_edit_dupliquer_ajout_article = $request->input('supprimer_edit_dupliquer_ajout_article');
        $role->edit_ajout_professionnel = $request->input('edit_ajout_professionnel');
        $role->declarer_heure_professionnel = $request->input('declarer_heure_professionnel');
        $role->voir_declaration_professionnel = $request->input('voir_declaration_professionnel');

        
        $role->save();
        
        return redirect()->back()->with('user', auth()->user())->with('success', 'Mise à jour effectuée avec succès');


       // 

      
        // Save the updated model to the database
      //  
    
      //  return redirect()->back()->with('user', auth()->user())->with('success', 'Mise à jour effectuée avec succès');

    }


    public function creation_roles(Request $request)
    {
        
        $role = new Role;

        $role->id = $request->input('id'); // set to null to let the database generate the ID

        $role->name = $request->input('nom_role');
        
        $role->estAutoriserDeVoirMembres =  0;
        $role->estAutoriserDeVoirClickAsso =  0;
        $role->estAutoriserDeRedigerArticle =  0;
        $role->estAutoriserDeGererSlider=   0;
        $role->estAutoriserDeVoirCategories=   0;
        $role->estAutoriserDeVoirFacture=   0;
        $role->estAutoriserDeVoirReduction =  0;
        $role->estAutoriserDeVoirArticleBoutique =  0;
        $role->estAutoriserDeVoirCategorieBoutique =  0;
        $role->estAutoriserDeVoirMessages =  0;
        $role->estAutoriserDeVoirHistorique =  0;
        $role->estAutoriserDeVoirCours =  0;
        $role->estAutoriserDeVoirAnimations =  0;
        $role->estAutoriserDeVoirStatsExports =  0;

        $role->estAutoriserDeVoirValiderCertificats =  0;
        $role->estAutoriserDeVoirGestionProfessionnels =  0;
        $role->estAutoriserDeVoirCalculDesSalaires =  0;
        $role->estAutoriserDeVoirValiderLesHeures =  0;
        $role->estAutoriserDeVoirGestionDesDroits =  0;
      
        $role->estAutoriserDeVoirSalles =  0;
        $role->estAutoriserDeVoirParametresGeneraux =  0;
        $role->estAutoriserDeVoirMessageGeneral =  0;
        $role->supprimer_edit_ajout_user =  0;
        $role->reinitialiser_mot_de_passe_user = 0;

        $role->supprimer_edit_facture =  0;
        $role->paiement_immediat =  0;
        $role->changer_status_facture =  0;
        $role->changer_designation_facture =  0;
        $role->supprimer_edit_ajout_categorie =  0;

        $role->supprimer_edit_dupliquer_ajout_article =  0;
        $role->edit_ajout_professionnel =  0;
        $role->declarer_heure_professionnel =  0;
        $role->voir_declaration_professionnel =  0;

        $role->save();

        return redirect()->back()->with('user', auth()->user())->with('success', 'Création du nouveau rôle avec succès');

    }

    public function methode_delete($id){

        $del_role  =  Role::where('id', $id)->delete();
        
        return redirect()->back()->with('user', auth()->user())->with('success', 'rôle supprimé avec succès');
    }

}

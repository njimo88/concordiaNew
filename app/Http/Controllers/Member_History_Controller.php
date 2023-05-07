<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\member_history; 

require_once(app_path().'/fonction.php');

class Member_History_Controller extends Controller
{
    //


    public function index()
    {
       // requete qui ramene la list des saisons en fonction de la table parametre.
        $saison_list = DB::table('parametre')->select('saison')->distinct('name')->orderBy('saison', 'ASC')->get();

         return view('member_history/index_member_history',compact('saison_list'))->with('user', auth()->user());
       
    }



    public function save_history(Request $request)
    {
        $saison = $request->input('saison') ;
        // call the function history_member pour effectuer la sauvegarde
        $answer = History_member($saison) ;

       return $answer;
       
    }


    public function consult_historique(Request $request){

        $history = member_history::where('saison',$request->input('saison'))->get();

        $saison_list = DB::table('parametre')->select('saison')->distinct('name')->orderBy('saison', 'ASC')->get();

        return view('member_history/historique',compact('saison_list','history'))->with('user', auth()->user());
       


    }


    function history_include_data(Request $request){
        
        $saison = $_POST['saison'];
        $s_saison = $request->input('saison');

        return redirect()->route('consult_historique', ['saison' => $saison])->with('submitted', true);

      //  return view('club/include-page',compact('users','saison'))->with('user', auth()->user());

    
    }


}


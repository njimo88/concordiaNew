<?php

namespace App\Http\Controllers;
use App\Models\Shop_article;
use App\Models\shop_article_1;
use PDF;

use App\Models\LiaisonShopArticlesBill;
use App\Models\User;
use App\Models\Shop_category;
use App\Models\appels;
use App\Models\Declinaison;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\Preset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

require_once(app_path().'/fonction.php');

class Controller_club extends Controller
{
    //
    function index_cours(Request $request) {
        /*--------------------------faire l'appel------------------------------ */
        ini_set('memory_limit', '512M');
    
        $saison_actu = $request->input('saison') ?? saison_active();
    
        /*------------------------------------- requetes pour les teachers ------------------------------*/
       
        $users_saison_active = User::select(
            'users.user_id',
            'users.name',
            'users.lastname',
            'users.phone',
            'users.birthdate',
            'users.email',
            'liaison_shop_articles_bills.id_shop_article',
            'bills_status.row_color',
            'bills.id'
        )
            ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
            ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')  // This is the new join to get the row_color
            ->where('shop_article.saison', $saison_actu)
            ->where('bills.status', '>', 9)
            ->distinct('users.user_id')
            ->orderBy('users.name', 'ASC')
            ->get(); 
        
    
        /* ------------------------------------------requetes pour l'admin------------------------------*/
        
        if(auth()->user()->roles->estAutoriserDeVoirArticle0_2) {
            // Admin users can see all types of articles (0, 1, 2)
            $shop_article_first = Shop_article::where('saison', $saison_actu)
                ->orderBy('title', 'ASC')
                ->get();
        } else{
            // Teachers can only see their own type 1 courses
            $shop_article_first = Shop_article::select('shop_article.*')
                ->leftJoin('shop_article_1', 'shop_article_1.id_shop_article', '=', 'shop_article.id_shop_article')
                ->where('shop_article.saison', $saison_actu)
                ->where('shop_article.type_article', '=', 1)
                ->whereRaw('JSON_CONTAINS(shop_article_1.teacher, \'[' . auth()->user()->user_id . ']\')')
                ->orderBy('saison', 'desc')
                ->orderBy('shop_article.title', 'asc')
                ->get();

        }
        

    
        $saison_list = Shop_article::select('saison')->distinct('saison')->orderBy('saison', 'ASC')->get();
    
        return view('club/cours_index',compact('saison_list','shop_article_first','users_saison_active','saison_actu'))->with('user', auth()->user());
    }
    
    public function generatePdf($id) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        ob_end_clean(); 
        ob_start();
        $course = Shop_article::find($id);
        $courseName = $course->title;
    
        Carbon::setLocale('fr');
        $currentDate = Carbon::now()->isoFormat('D MMMM YYYY');
        
        $saison = $course->saison;
        $nextSaison = $saison + 1;
    
        $personnes = User::select(
            'users.user_id',
            'users.name',
            'users.lastname',
            'users.birthdate',
            'liaison_shop_articles_bills.id_shop_article',
            'bills.id',
            'bills.status'
        )
            ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
            ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
            ->where('shop_article.id_shop_article', $id)
            ->distinct('users.user_id')
            ->orderBy('users.name', 'ASC')
            ->get(); 
    
            $pdfContent = "
            

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap');
        body, table {
            font-family: 'Roboto', sans-serif;
            font-size: 10px; // Réduire la taille de la police
        }
        
        table {
            border-collapse: collapse;
            width: 100%; 
        }

        td, th {
            min-width: 10px;
            border: 1px solid #333; 
            padding: 5px; 
            white-space: nowrap; // Nom Prénom sur une seule ligne
        }

        th {
            height: 110px;
        }

        .header {
            position: relative;
            width: 100%;
        }

        .header-text {
            font-size: 16px;
            width: 80%;
            float: left;
        }

        .logo-container {
            width: 20%;
            float: right;
            text-align: right;
            height: 100px ;
        }

        .logo {
            height: 100px;  // Taille naturelle
            width: auto;
        }
    </style>
            <div class='header'>
                <div class='header-text'>
                    Liste du Groupe <b>\"{$courseName} ({$saison}-{$nextSaison})\"</b> au {$currentDate}
                </div>
                <div class='logo-container'>
                    <img class='logo' src='https://www.gym-concordia.com/assets/images/LogoHB.png' alt='Logo'>
                </div>
                <div style='clear:both;'></div>
            </div>
            <br>
            <div style='text-decoration: underline;font-size: 16px;'>{$courseName}</div>
            <br>
            <table border='1'>
            <thead>
        <tr>
            <th>ID</th>
            <th>Nom Prénom</th>
            <th>Date Naiss</th>";

for ($i = 1; $i <= 30; $i++) { 
    $pdfContent .= "<th></th>";
}

$pdfContent .= "</tr>
    </thead>
            <tbody>";
    
        $counter = 0;
        foreach ($personnes as $person) {
            if ($person->status != 1) {
                $counter++;
            }
            $formattedDate = date('d/m/Y', strtotime($person->birthdate));
            $pdfContent .= "
            <tr " . ($person->status == 1 ? "style='text-decoration: line-through;'" : "") . ">
                <td>" . ($person->status != 1 ? $counter : "") . "</td>
                <td>{$person->name} {$person->lastname}</td>
                <td>{$formattedDate}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>";
        }
    
        $pdfContent .= "
            </tbody>
        </table>";
    
        $pdf = PDF::loadHTML($pdfContent);
        $pdf->setPaper('a4', 'landscape'); 
        header('Content-Type: application/pdf');
        return $pdf->stream();
    }
    
    

    public function get_data_table(Request $request, $article_id){


        return $article_id ;


        
    }




    function index_include(Request $request){
        
        $saison = $_POST['saison'];
        $s_saison = $request->input('saison');


        $users = User::select('users.user_id', 'users.name', 'users.email','liaison_shop_articles_bills.id_shop_article')
        ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
        ->join('shop_article','shop_article.id_shop_article','=','liaison_shop_articles_bills.id_shop_article')->where('saison', $saison)->get();

    

        return redirect()->route('index_cours', ['saison' => $saison])->with('submitted', true);

      //  return view('club/include-page',compact('users','saison'))->with('user', auth()->user());

    
    }

   

    public function display_form_cours($id)
      {
    // Retrieve the data for the specified ID from the database
    $shop_article = Shop_Article::find($id);
    $buyers = Donne_User_article_Paye($id);

    $user = User::get() ;
    $requete_articles = Shop_article::get() ;

   //dd($user) ;
  //  return view('/shop_article_cours_ajax', compact('user','buyers','requete_articles'))->with('user', auth()->user());


  } 

public function form_appel_method($id){

    $shop_article = Shop_Article::find($id);
    $buyers = Donne_User_article_Paye($id);
  
  
    $users = User::select("*")->whereIn('user_id', $buyers)->get();

    $the_id = $id ;

   // dd($users);            
      
    return view('/formulaire_appel', compact('users','buyers','the_id'))->with('user', auth()->user());
    


}

public function display_info_user($id){

    $the_id = $id ;
    $info_user = User::where('user_id',$id)->get() ;
    
    $requete_user_family_id = User::where('user_id',$id)->value('family_id');
    $tab =  User::where('family_id', $requete_user_family_id) ->where('family_level','parent')->get();




    return view('club/modal_info', compact('info_user','tab','the_id'))->with('user', auth()->user());


}


public function modif_user($id,Request $request){

    $user = User::find($id);

    $validatedData = $request->validate( [
        'username' => 'nullable|string|max:255',
        'name' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
        'lastname' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
        'email' => [ 'nullable','string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
        'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
        'profession' => 'string|max:191',
        'birthdate' => 'required|date|before:today',
        'address' => 'required',
        'zip' => 'required|numeric',
        'city' => 'required',
        'nationality' => 'required',
        'licenceFFGYM' => ['nullable','regex:/^\d{5}\.\d{3}\.\d{5}$/'],

    ], $messages = [
        'username.required' => "Le champ nom d'utilisateur est requis.",
        'username.max' => "Le nom d'utilisateur ne doit pas dépasser 255 caractères.",
        'name.required' => "Le champ nom est requis.",
        'name.alpha' => "Le nom doit être une chaîne de caractères.",
        'lastname.required' => "Le champ prénom est requis.",
        'lastname.alpha' => "Le prénom doit être une chaîne de caractères.",
        'email.required' => 'Le champ :attribute est requis.',
        'email' => "Le format de l'adresse e-mail est invalide.",
        'email.unique' => "L'adresse e-mail est déjà utilisée.",
        'password.required' => "Le champ mot de passe est requis.",
        'password.min' => "Le mot de passe doit contenir au moins 8 caractères.",
        'password.confirmed' => "La confirmation du mot de passe ne correspond pas.",
        'phone.required' => "Le champ numéro de téléphone  est requis.",
        'phone.regex' => "Le format du numéro de téléphone est invalide.",
        'gender.required' => "Le champ sexe est requis.",
        'birthdate.required' => 'La date de naissance est requise',
        'birthdate.date' => 'Format de date non valide',
        'birthdate.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
        'profession.alpha' => "La profession doit être une chaîne de caractères.",
        'address.required' => "Le champ address est requis.",
        'zip.required' => "Le champ code postal est requis.",
        'zip.regex' => "Le code postal doit être au format 12345 ou 12345-1234.",
        'city.required' => "Le champ ville est requis.",
        'city.alpha' => "La ville doit être une chaîne de caractères.",
        'country.required' => "Le champ pays est requis.",
        'licenceFFGYM.required' => "Le champ licence FFGYM est requis.",
        'licenceFFGYM.regex' => "Le format de la licence FFGYM est invalide.",
    ]);

  
        $user->update($request->all());

        return redirect()->route('index_cours')->with('success', ' mise à jour effectuée avec succès');
}

/*

public function enregister_appel_method_test($id , Request $request){

            $appels           = new appels ;

            $appels->id_cours = $id ;
            $appels->date     = $request->input('date_appel');

            $thedate = $appels->date ;  

    
           
            $tab_user = (array)$request->input('user_id');
            $length_tab_user = count($tab_user);

            $tab_presence =  (array)$request->input('marque_presence');
            $length_tab_presence = count($tab_presence);



            dd($tab_user);

            $ladifference = $length_tab_user - $length_tab_presence  ; // je recupere la difference de taille entre le tableau des users et le nombre d'element checked
           

            if ( $length_tab_user == $length_tab_presence) {

                        $pairedArray = array_combine($tab_user,$tab_presence );
                     $my_json  = json_encode($pairedArray,JSON_NUMERIC_CHECK);
            }else{

                $myArray = array_fill(0, $ladifference, 0);
                $mergedArray = array_merge($tab_presence,$myArray);

                $pairedArray = array_combine($tab_user, $mergedArray);
              
                 $my_json  = json_encode($pairedArray,JSON_NUMERIC_CHECK);

            }

            $appels->presents = $my_json ;
       
          //  $appels->save() ;


           // return redirect()->route('index_cours')->with('success', " l'appel a été effectué avec succès");
        

}

*/


public function enregister_appel_method($id , Request $request){

   
    $tab_presence2 = [] ;
    $tab_presence_final = [] ;
    $tab_presence3 = [] ;
    $appels           = new appels ;

    $appels->id_cours = $id ;
     
    $thedate  = $request->input('date_appel');
    $appels->date = $thedate ;

    $tab_user = (array)$request->input('user_id');
    $tab_presence =  (array)$request->input('marque_presence'); // informations venant des checkboxes
   
    
    // Use array_keys() to extract only the keys of the array (on recupere les indices qui sont en fait les ID des users)
        $keys = array_keys($tab_presence);

    
                foreach($tab_user as $user){

                            if (in_array($user,$keys)){          

                               // $tab_presence2[$user]  = 1 ;
                                $tab_presence2 = array(
                                    $user => 1 
                                );
                        
                            }else{

                              //  $tab_presence2[$user]  = 0 ;
                                $tab_presence2 = array(
                                    $user => 0 
                                );
                                  
                                }

                                $tab_presence3[] =  $tab_presence2  ;
                                $tab_presence2 = []  ;
                   
                   
                }
                           
                $my_json  = json_encode($tab_presence3,JSON_NUMERIC_CHECK);

                $new_data = [];

                foreach ($tab_presence3 as $d) {
                    foreach ($d as $key => $value) {
                        $new_data[$key] = $value;
                    }
                }

                // Output the new data as a JSON string
                $my_json = json_encode($new_data);
              
                $appel_verif = appels::where('id_cours', $id)->where('date',$thedate)->get();
               // dd($appel_verif);
               
                if ($appel_verif->count() > 0) {
                   appels::where('id_cours', $id)->where('date', $thedate)->update(['presents' => $my_json]);
                   return redirect()->route('index_cours')->with('success', " l'appel a été modifié  avec succès");

                  

                }else{

                   $appels->presents = $my_json ;
     
                   $appels->save() ;
                   return redirect()->route('index_cours')->with('success', " l'appel a été effectué avec succès");

                  
                }
               
               

                /*$appel_verif = appels::where('id_cours', $id)->where('date',$thedate)->get();

                if ($appel_verif){

                    appels::where('id_cours', $id)
                    ->where('date', $thedate)
                    ->update(['presents' =>  $my_json]);

                    return  $appel_verif ;
                   

                }else{

                   // 
                    return 0 ;
                   
                   // $appels->save() ;
                }
                */
                                                            





             

  return redirect()->route('index_cours')->with('success', " l'appel a été effectué avec succès");


}



function display_historique_method($id){
    $id_cours = $id ;
    $tab = [] ;
    $attendance = [];

    $appel = appels::where('id_cours', $id)->get();

    foreach ($appel as $data) {
        foreach (json_decode($data->presents, true) as $userId => $presenceStatus) {
            $attendance[$userId][$data->date] = $presenceStatus;
        }
    }

    $users = User::select("*")
                 ->whereIn('user_id', array_keys($attendance))
                 ->orderBy('name', 'ASC')
                 ->get();

    return view('club/historique_view', compact('id_cours', 'appel', 'users', 'attendance'))->with('user', auth()->user());
}

 
function StatsExports (){
    $saison_list = Shop_article::select('saison')->distinct('name')->orderBy('saison', 'DESC')->get();
    return view('club/stats_export',compact('saison_list'));
}
 
public function exportCSV(Request $request)
{
    $saison = $request->input('saison');
    $members = DB::table('liaison_shop_articles_bills')
        ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('shop_article.type_article', 0)
        ->where('bills.status', '>', 9)
        ->where ('shop_article.saison', $saison)
        ->select(DB::raw('DISTINCT liaison_shop_articles_bills.id_user'))
        ->get();

        $callback = function() use ($members, $saison) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Id', 'Prenom', 'Nom', 'Sexe', 'Date de Naissance', 'Pays de Naissance', 'Adresse', 'Code Postal', 'Ville', 'Pays', 'Certificat Médical', 'Email', 'Téléphone', 'Groupe']);
        
            foreach ($members as $member) {
                $memberFromDb = DB::table('users')
                    ->where('users.user_id', $member->id_user)
                    ->first();
                if ($memberFromDb) {
                    $today = now();
                    $medicalCertificateDate = $today->startOfMonth()->toDateString();
        
                    $articles = DB::table('liaison_shop_articles_bills')
                        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
                        ->where('shop_article.type_article', 1)
                        ->where('shop_article.saison', $saison)
                        ->where('liaison_shop_articles_bills.id_user', $member->id_user)
                        ->select('shop_article.title')
                        ->get();
                        
                    $group = $articles->isEmpty() ? ['Sans'] : $articles->pluck('title')->toArray();
        
                    $row = [
                        $member->id_user,
                        $memberFromDb->lastname, 
                        $memberFromDb->name,
                        $memberFromDb->gender,
                        $memberFromDb->birthdate,
                        $memberFromDb->nationality,
                        $memberFromDb->address,
                        $memberFromDb->zip,
                        $memberFromDb->city,
                        $memberFromDb->country,
                        $medicalCertificateDate,
                        $memberFromDb->email,
                        $memberFromDb->phone,
                        implode(",", $group) 
                    ];
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ];
        
        return new StreamedResponse($callback, 200, $headers);
        

}

public function exportCSVproduit(Request $request)
{
    $saison = $request->input('saison');
    $delimiter = ';';

    $users_saison_active = User::select(
        'shop_article.title as produit',
        'users.name',
        'users.lastname',
        'users.gender',
        'users.birthdate',
        'users.address',
        'users.zip as postal_code',
        'users.city',
        DB::raw('CAST(users.phone AS CHAR) as phone'),  
        'users.email',
        'users.nationality',
        'users.family_id',
        'users.role'
    )
    ->join('liaison_shop_articles_bills', 'liaison_shop_articles_bills.id_user', '=', 'users.user_id')
    ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
    ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
    ->where('shop_article.saison', $saison)
    ->where('bills.status', '>', 9)
    ->distinct('users.user_id')
    ->orderBy('shop_article.title', 'ASC')
    ->orderBy('users.name', 'ASC')
    ->get();
    
    $response = new StreamedResponse(function() use ($users_saison_active, $delimiter) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Produit', 'Nom', 'Prénom', 'Sexe', 'Date de naissance', 'Age', 'Nationalité', 'Adresse', 'Code postal', 'Ville', 'Téléphone', 'Email', 'ID Family', 'Role'], $delimiter);

        foreach ($users_saison_active as $user) {
            $birthdate = new \DateTime($user->birthdate);
            $now = new \DateTime();
            $age = $now->diff($birthdate)->y;
            fputcsv($handle, [$user->produit, $user->name, $user->lastname, $user->gender == 'male' ? 'Homme' : 'Femme', $birthdate->format('d/m/Y'), $age, $user->nationality, $user->address, $user->postal_code, $user->city, $user->phone, $user->email, $user->family_id, $user->role], $delimiter);
        }
        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
    return $response;
}


public function deleteMethod($id)
    {
        $declinaison = Declinaison::find($id);
        $article = Shop_article::find($declinaison->shop_article_id);
        if ($declinaison) {
            $declinaison->delete();
            $article->updateInitialStock();
            return response()->json([
                'success' => true,
            ]);
        }
        

        return redirect()->back()->with('error', 'Declinaison non trouvé');
    }

    public function addMethod(Request $request)
{
    $declinaison = new Declinaison();
    $declinaison->shop_article_id = $request->shop_article_id;
    $declinaison->libelle = $request->libelle;
    $declinaison->stock_ini_d = $request->stock_ini_d;
    $declinaison->stock_actuel_d = $request->stock_ini_d;
    $declinaison->save();

    $shopArticle = Shop_article::find($request->shop_article_id);
    $shopArticle->updateInitialStock();


    return response()->json([
        'success' => true,
        'libelle' => $declinaison->libelle,
        'Id' => $declinaison->id,
        'deleteRoute' => route('delete.declinaison', $declinaison->id)
    ]);
}
}
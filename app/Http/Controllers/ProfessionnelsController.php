<?php

namespace App\Http\Controllers;

use App\Models\Professionnels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cart;
use App\Models\CalculSalaire;
use App\Models\BasketAn;
use App\Models\Declaration;
use Illuminate\Support\Facades\Storage;
use PDF; 
use Illuminate\Support\Facades\Mail;
use App\Mail\DeclarationEmail;

require_once(app_path().'/fonction.php');



class ProfessionnelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestion()
    {
        $professionals = Professionnels::pluck('id_user')->toArray();
        $users = User::whereNotIn('user_id', $professionals)->get();

        $pro = Professionnels::all();
        return view('admin.professionnels.gestion',compact('pro','users'))->with('user', auth()->user());
    }

    public function declarerHeure()
    {
        $pro = Professionnels::where('id_user', auth()->user()->user_id)->get();
        return view('admin.professionnels.gestion',compact('pro'));
    }


    public function refuserDeclaration(Request $request)
{
    $declaration_id = $request->input('declaration_id'); 
    $declaration = Declaration::find($declaration_id);
    if ($declaration) {
        $declaration->soumis = 0;
        $declaration->save();
        return redirect('/valider-heures')->with('success', 'La déclaration a été refusée.');
    } else {
        return redirect('/valider-heures')->with('error', 'La déclaration n\'a pas été trouvée.');
    }
}


    public function validerDeclaration(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|integer',
        'annee' => 'required|integer',
        'mois' => 'required|integer',
        'details' => 'required|array',
        'totalHeures' => 'required|numeric',
        'totalConges' => 'required|numeric',    
        'totalMaladie' => 'required|numeric',
    ]);

    $declaration = Declaration::firstOrNew(
        [
            'user_id' => $validated['user_id'],
            'annee' => $validated['annee'],
            'mois' => $validated['mois'],
        ]
    );



    $declaration->soumis = 1;
    $declaration->heures_realisees = $validated['totalHeures'];
    $declaration->jours_conges = $validated['totalConges'];
    $declaration->jours_maladie = $validated['totalMaladie'];
    $declaration->details = $validated['details'];

    $declaration->save();

    return response()->json(['status' => 'success', 'declaration' => $declaration]);
}

public function validerDec(Request $request){
    $declaration_id = $request->input('declaration_id'); 
    $declaration = Declaration::find($declaration_id);
    $pro = Professionnels::where('id_user', $declaration->user_id)->first();
    $pro->SoldeConges = $pro->SoldeConges + 2.5;
    $pro->LastDeclarationMonth = ($pro->LastDeclarationMonth % 12) + 1;
    $pro->OldHeuresRealisees = $pro->OldHeuresRealisees + $declaration->heures_realisees;

    if ($pro->LastDeclarationMonth == 1) {
        $pro->LastDeclarationYear = $pro->LastDeclarationYear + 1;
    }
    
    $pro->save();
    $declaration->valider = 1;
    $declaration->save();

    session()->flash('success', 'La déclaration a été validée.');

    $response = $this->generatePDF($declaration_id);
    
    // After the PDF has been generated, update the saison and OldHeuresRealisees as needed
    if ($pro->LastDeclarationMonth == 8) {
        $pro->Saison = $pro->Saison + 1;
        $pro->OldHeuresRealisees = 0;
        $pro->save();
    }
    
    return $response;
}



    public function saveDeclaration(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|integer',
        'annee' => 'required|integer',
        'mois' => 'required|integer',
        'details' => 'required|array',
        'totalHeures' => 'required|numeric',
        'totalConges' => 'required|numeric',    
        'totalMaladie' => 'required|numeric',
    ]);

    $declaration = Declaration::updateOrCreate(
        [
            'user_id' => $validated['user_id'],
            'annee' => $validated['annee'],
            'mois' => $validated['mois'],

        ],
        [
            'heures_realisees' => $validated['totalHeures'],
            'jours_conges' => $validated['totalConges'],
            'jours_maladie' => $validated['totalMaladie'],
            'details' => $validated['details'],
        ]
    );

    return response()->json(['status' => 'success', 'declaration' => $declaration]);
}

public function engistrerDeclaration(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|integer',
        'annee' => 'required|integer',
        'mois' => 'required|integer',
        'details' => 'required|array',
    ]);

    $declaration = Declaration::firstOrNew(
        [
            'user_id' => $validated['user_id'],
            'annee' => $validated['annee'],
            'mois' => $validated['mois'],
        ]
    );

    $declaration->details_admin = $validated['details'];
    $declaration->save();
    return response()->json(['status' => 'success', 'declaration' => $declaration]);
}

   

    public function simuleSalary(Request $request)
{
    $volumeHebdo = $request->input('volHebdo');
    $groupe = $request->input('groupe');
    $embauche = $request->input('embauche');

    $result = DB::table('divers')->select('SMC', 'SMIC')->first();
    $SMC = $result->SMC;
    $SMIC = $result->SMIC;

    $calculSalaire = new CalculSalaire();
    $salaireMin = number_format($calculSalaire->CalculSalaireCCNS($volumeHebdo, $groupe, $embauche, $SMC, $SMIC), 2, ',', '');
    $primeAnciennete = number_format($calculSalaire->CalculPrimeCCNS($volumeHebdo, $groupe, $embauche, $SMC, $SMIC), 2, ',', '');

    $data['simuleSalary'] = $salaireMin;
    $data['simulePrime'] = $primeAnciennete;

    $arrayProfessionals = DB::table('users')
        ->join('professionals', 'users.id', '=', 'professionals.id_user')
        ->select('users.lastname', 'users.firstname', 'professionals.Salaire', 'professionals.Prime')
        ->get();

    $resultSalary = DB::table('divers')->select('SMC', 'SMIC')->first();
    $data['SMIC'] = $resultSalary->SMIC;
    $data['SMC'] = $resultSalary->SMC;

    $resultatrenvoye = array();

    foreach ($arrayProfessionals as $professional) {
        $calculSalaire = app()->make(CalculSalaire::class);
        $salarie = $calculSalaire->AfficheSalarie($professional->id_user);

        $salarie['professional'] = $professional->lastname . ' ' . $professional->firstname;
        $salarie['salaireActuel'] = $professional->Salaire;
        $salarie['primeActuel'] = $professional->Prime;

        $resultatrenvoye[] = $salarie;
    }

    $data['resultatrenvoye'] = $resultatrenvoye;

    return view('admin.professionnels.calculSalary', compact('data'))->with('user', auth()->user());

}


    public function calculSalary()
    {
        $array_professionals = $this->getProfessionals();
        $result_salary = $this->getSalaireMinimum();
        $data['SMIC'] = $result_salary[0]->SMIC;
        $data['SMC'] = $result_salary[0]->SMC;
    
        $resultatrenvoye = [];
    
        foreach ($array_professionals as $professional) {
            $calculSalaire = app()->make(CalculSalaire::class);
            $resultat = $calculSalaire->AfficheSalarie($professional->id_user);
            $resultatrenvoye[] = [
                'resultat' => $resultat,
                'professional' => $professional->lastname . ' ' . $professional->firstname,
                'salaireActuel' => $professional->Salaire,
                'anciennete' => $resultat['anciennete'],
                'salaireMin' => $resultat['salaireMin'],
                'primeAnciennete' => $resultat['primeAnciennete'],
                'primeActuel' => $professional->Prime,
                'Groupe' => $professional->Groupe,
                'id_user' => $professional->id_user,
            ];
        }
    
        $data['resultatrenvoye'] = $resultatrenvoye;
        $basketAn = app()->make(BasketAn::class);
        $data['totalItems'] = count($basketAn->get_cart());
    
        return view('admin.professionnels.calculSalary', compact('data'))->with('user', auth()->user());
    }

    public function modifySM(Request $request)
{
    $smic = $request->input('smic');
    $smc = $request->input('smc');

    DB::table('divers')->update([
        'SMIC' => $smic,
        'SMC' => $smc,
    ]);

    $array_professionals = $this->getProfessionals();
    $result_salary = $this->getSalaireMinimum();

    $data['SMIC'] = $result_salary[0]['SMIC'];
    $data['SMC'] = $result_salary[0]['SMC'];

    $i = 0;
    $resultatrenvoye = [];

    foreach ($array_professionals as $professional) {
        $calculSalaire = app()->make(CalculSalaire::class);
            
        $resultatrenvoye[$i] = $resultat = $calculSalaire->AfficheSalarie($professional->id_user);

        $resultatrenvoye[$i]['professional'] = $professional['lastname'] . " " . $professional['firstname'];
        $resultatrenvoye[$i]['salaireActuel'] = $professional['Salaire'];
        $resultatrenvoye[$i]['primeActuel'] = $professional['Prime'];
        $i++;
    }

    $data['resultatrenvoye'] = $resultatrenvoye;
    $basketAn = app()->make(BasketAn::class);
    $data['totalItems'] = count($basketAn->get_cart());
    return view('admin.professionnels.calculSalary', compact('data'))->with('user', auth()->user());
}

    

    public function getProfessionals($id_user = null)
{
    if ($id_user == null) {
        $array_professionals = DB::table('users_professionals')->get()->toArray();
    } else {
        $array_professionals = DB::table('users_professionals')->where('id_user', $id_user)->get()->toArray();
    }

    return $array_professionals;
}

public function getSalaireMinimum()
{
    $result_salary = DB::table('divers')->get()->toArray();

    return $result_salary;
}


    public function  editPro(Request $request, $user_id)
    {
       
        $user = Professionnels::find($user_id);
        $validatedData = $request->validate( [
        'lastname' => ['alpha', 'max:255'],
        'firstname' => [ 'alpha', 'max:255'],
        'matricule'=> 'integer|max:191',
        'VolumeHebdo'=> 'integer|max:191',
        'Groupe'=> 'integer|max:191',
        'Lundi'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Mardi'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Mercredi'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Jeudi'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Vendredi'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Samedi'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Dimanche'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Salaire'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'Prime'=> 'regex:/^\d*(\.\d{2})?$/|max:191',
        'masque'=> 'integer|max:191',
        'Embauche'=> 'date|max:191',
        ]);
        $user->update($request->all());
        return redirect()->route('Professionnels.gestion')->with('success', $user->firstname . ' ' . $user->lastname . ' a été mis à jour avec succès');
    }

    public function addPro(Request $request)
    {
        $selectedUserId = $request->input('selected_user_id');
        $user = User::find($selectedUserId);
        $addUser = new Professionnels();
        $addUser->id_user = $selectedUserId;
        $addUser->lastname = $user->name;
        $addUser->firstname = $user->lastname;
        $addUser->email = $user->email;
        $addUser->save();
        return redirect()->route('Professionnels.gestion')->with('success', $addUser->firstname . ' ' . $addUser->lastname . ' a été ajouté aux professionnels avec succès');
    }

    public function declarationList($id_user)
{
    $array_file = [];
    $new_array_file = [];

    $path_1 = base_path() . '/public/employee_documents/2-demande/' . $id_user . '*.pdf';
    $path_2 = base_path() . '/public/employee_documents/3-validation/' . $id_user . '*.pdf';

    $files_1 = glob($path_1);
    $files_2 = glob($path_2);

    $files = array_merge($files_1, $files_2);

    foreach ($files as $file) {
        $explode = explode('/', $file);
        $explode2 = explode('-', end($explode));
        $explode3 = explode('.', $explode2[2]);
        $year = $explode2[1];
        $month = $explode3[0];

        if ($month < 10) {
            $month = "0" . $month;
        }

        $date = $year . $month;
        $date = (int) $date;

        $array_file[$date] = $file;
    }

    arsort($array_file, SORT_NATURAL);

    foreach ($array_file as $file) {
        $path = str_replace(base_path().'/public/', '', $file);

        $new_array_file[] = [
            'path' => $path,
            'periode' => $this->getPeriode($path),
        ];
    }
    
    return view('admin.modals.declarationList', [
        'array_file' => $new_array_file,
    ]);
}

private function getPeriode($path)
{
    $explode = explode('/', $path);
    $explode2 = explode('-', end($explode));
    $explode3 = explode('.', $explode2[2]);
    $year = $explode2[1];
    $month = $explode3[0];

    if ($month < 10) {
        $month = "0" . $month;
    }

    $date = '1-' . $month . "-" . $year;
    setlocale(LC_TIME, 'fr_FR');
    $format_date = strftime("%B %Y", strtotime($date));
    $periode = utf8_encode($format_date);

    return $periode;
}

public function declarationHeures($id)
{
    $user_id = $id;
    $pro = Professionnels::where('id_user', $user_id)->first();

    // Calculate next month and year
    $nextMonth = $pro->LastDeclarationMonth == 12 ? 1 : $pro->LastDeclarationMonth + 1;
    $nextYear = $pro->LastDeclarationMonth == 12 ? $pro->LastDeclarationYear + 1 : $pro->LastDeclarationYear;
    // Check if the professional has a row in the declarations table
    $declaration = Declaration::where('user_id', $user_id)
    ->where('mois',  $nextMonth)
    ->where('annee',  $nextYear)
    ->first();

    // Pass the declaration data to the view if it exists
    if ($declaration) {
        return view('admin.professionnels.declarationHeures', compact('user_id', 'pro', 'declaration'));
    }
    
    // Display the simple view if no declaration data exists
    return view('admin.professionnels.declarationHeures', compact('user_id', 'pro'));
}



    public function declaration($id, Request $request)
    {
        $user_id = $id;
        $info = $request->all();
        return view('admin.professionnels.declaration',compact('user_id','info'))->with('user', auth()->user());
    }

    



function checkFile()
{
    $professionals = Professionnels::all();
    $array_users = array();
    $array_general = array();

    foreach ($professionals as $row) {
        $id_professionnal = $row['id_user'];
        $directory = "employee_documents/3-validation/" . $id_professionnal;
        $files = Storage::disk('public')->files($directory);
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'csv') {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                dd($filename);
                $explode = explode('-', $filename);
                $year = $explode[1];
                $month = $explode[2];

                $date = '1-' . $month . "-" . $year;
                setlocale(LC_TIME, 'fr_FR');
                $format_date = strftime("%B %Y", strtotime($date));
                $periode = utf8_encode($format_date);

                $fichier_demande_csv = $directory . '/' . $id_professionnal . '-' . $year . '-' . $month . '.csv';
                $fichier_demande_pdf = $directory . '/' . $id_professionnal . '-' . $year . '-' . $month . '.pdf';

                if (Storage::disk('public')->exists($fichier_demande_csv)) {
                    if (!Storage::disk('public')->exists($fichier_demande_pdf)) {
                        $array_users['id_user'] = $id_professionnal;
                        $array_users['name'] = $row['lastname'] . " " . $row['firstname'];
                        $array_users['periode'] = $periode;

                        array_push($array_general, $array_users);
                    }
                }
            }
        }
    }
    dd($array_general);

    return $array_general;
}

public function valideHeure()
    {
       $declaration = Declaration::where('soumis', 1)
       ->where('valider', 0)
       ->join('users', 'users.user_id', '=', 'declarations.user_id')
       ->select('users.name', 'users.lastname','declarations.*')
       ->get();

         return view('admin.professionnels.valider_heures',compact('declaration'));
        
    }

    public function voir_declaration ($declaration_id)
    {
    
        $declaration = Declaration::where('id', $declaration_id)->first();
        $user_id = $declaration->user_id;
        $pro = Professionnels::where('id_user', $user_id)->first();
        return view('admin.professionnels.voir_declaration', compact('user_id', 'pro', 'declaration'));
        
    }

    
/*
    public function generatePDF($declaration_id)
    {
        $declaration = Declaration::where('id', $declaration_id)->first();
        $user_id = $declaration->user_id;
        $user = User::find($user_id); 
        $pro = Professionnels::where('id_user', $user_id)->first();
    
        $data = [ 
            'user_id' => $user_id, 
            'pro' => $pro, 
            'declaration' => $declaration,
        ];
    
        $pdf = PDF::loadView('admin.professionnels.profPDF', $data);
      
        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
        $monthName = ucfirst(strftime("%B", mktime(0, 0, 0, $declaration->mois+1, 10)));

$emailContent = "<p><i>[Ceci est un message automatique]</i></p>

    <p>Bonjour,</p>

    <p>Veuillez trouver ci-joint ma déclaration d'heures pour le mois de : ".$monthName." ".$declaration->annee."</p>

    <p>Cordialement</p>";

    
        $attachmentName = $user->user_id.'-'.$declaration->annee.'-'.$declaration->mois.'.pdf';
      
        // Return the PDF as a downloadable response
        return $pdf->download($attachmentName);
    }
    

*/

public function generatePDF($declaration_id)
{
    $declaration = Declaration::where('id', $declaration_id)->first();
    $user_id = $declaration->user_id;
    $user = User::find($user_id); 
    $pro = Professionnels::where('id_user', $user_id)->first();

    $data = [ 
        'user_id' => $user_id, 
        'pro' => $pro, 
        'declaration' => $declaration,
    ];

    $pdf = PDF::loadView('admin.professionnels.profPDF', $data);
    $pdfOutput = $pdf->output(); 

    setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
    $monthName = ucfirst(strftime("%B", mktime(0, 0, 0, $declaration->mois, 10)));

$emailContent = "<p><i>[Ceci est un message automatique]</i></p>

    <p>Bonjour,</p>

    <p>Veuillez trouver ci-joint ma déclaration d'heures pour le mois de : ".$monthName." ".$declaration->annee."</p>

    <p>Cordialement</p>";



    $attachmentName = $user->user_id.'-'.$declaration->annee.'-'.$declaration->mois.'.pdf';
    $pdf->save(public_path('employee_documents/3-validation/'.$attachmentName));

    $attachment = base64_encode($pdfOutput); 

$emailSubject = $monthName . " " . $declaration->annee . " - Fiche Heures";

Mail::to(['president@gym-concordia.com', 'tresorier@gym-concordia.com'])
        ->cc($user->email)
        ->send(new DeclarationEmail($emailContent, $attachment, $attachmentName, $user->username, $emailSubject));


    return redirect()->route('proffesional.valideHeure')->with('success', 'Votre déclaration a bien été envoyée !');
}
}    


    
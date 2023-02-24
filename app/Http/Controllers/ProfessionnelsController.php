<?php

namespace App\Http\Controllers;

use App\Models\Professionnels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cart;

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
        return view('admin.professionnels.declarationHeures',compact('user_id'))->with('user', auth()->user());
    }



}

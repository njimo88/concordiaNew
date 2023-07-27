<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\shop_article_1;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\statistiques_visites;

require_once(app_path().'/fonction.php');





class n_AdminController extends Controller
{

   

    public function index()

    {

        $determinSecValue = SystemSetting::where('name', 'determin_sec')->first()->value;
        $annee_actu = saison_active() ; // recupere l'annee de la saison active
                    // recupere l'annee du debut des activites de l'association (au depart 2015)
                    $date_de_rentree = DB::table('system')->where('name','date_de_rentree')->first('value');
                    $annee_creation = $date_de_rentree->value;

                    // la difference entre l'annee de la saison actuelle et celle de la saison encore
                    $diff_year = $annee_actu - $annee_creation ;

                    // si l'ecart entre les deux annees est superieures a 10 alors on fait une
                    // requete qui va incrementer d'une unite l'annee de creation pour qu'on reste toujours sur 10 ans d'ecart dans la BD 
                    if ($diff_year>10){
                        DB::table('system')->where('name', 'date_de_rentree')->increment('value');
                    }
                   
                    $visitorCount = statistiques_visites::where('page', '=', '/')->first();
                            if($visitorCount){
                                
                                $nbre_visit = $visitorCount->nbre_visitors ;
                            }else{
                                $nbre_visit = 0 ;
                            }
               

                    $get_stat_pages = statistiques_visites::where('page', '!=', '/')->orderBy('nbre_visitors', 'desc')
                    ->limit(10)->get();

                    $saison_actu = saison_active() ;

                    $shop_article_lesson =  shop_article_1::select('shop_article_1.teacher', 'shop_article.title','shop_article_1.id_shop_article','shop_article.stock_actuel','shop_article.stock_ini')
                    ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison', $saison_actu)->get();
                   
                   return view('admin.index',compact('annee_creation','annee_actu','nbre_visit','get_stat_pages','shop_article_lesson' , 'determinSecValue'))->with('user', auth()->user());


    }

    public function PortOuvindex()
    {
        return view('admin.portOuv')->with('user', auth()->user());
    }

    public function getUsers(Request $request)
{
    if ($request->ajax()) {
    
        $cacheKey = 'users:data' . ($request->has('search') ? ':' . $request->get('search')['value'] : '');

        $data = Cache::get($cacheKey);
        $user = Auth::user();
        if (!$data) {
            $query = User::select('user_id', 'email', 'username', 'phone', DB::raw("CONCAT(lastname, ' ', name) as full_name"),'birthdate');

            if ($request->has('search') && $request->get('search')['value']) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%" . $request->get('search')['value'] . "%")
                          ->orWhere('email', 'like', "%" . $request->get('search')['value'] . "%")
                          ->orWhere('username', 'like', "%" . $request->get('search')['value'] . "%")
                          ->orWhere('phone', 'like', "%" . $request->get('search')['value'] . "%");
                });
            }

            $data = $query->get();
            Cache::put($cacheKey, $data, 60);
        }

        return datatables()->of($data)
        ->editColumn('birthdate', function($user) {
            return date("Y-m-d", strtotime($user->birthdate));
        })
            ->addColumn('action', function($user){
                return '<span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Réinitialiser le mot de passe">' .
                    '<img data-user-id="' . $user->user_id . '" class="Resetpass editbtn2 mx-2" src="' . asset('assets/images/rotate.png') . '">' . 
                    '</span>' . 
                    '<a data-user-id="' . $user->user_id . '" type="button" class="editusermodal user-link a text-black" href="#">' .
                    '<i class="fas fa-edit"></i>' . // replace with your own icon
                    '</a>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    return view('admin.portOuv');
}

    



    public function members(Request $request)
    {
        $roles = Role::all();
        return view('admin.members', compact('roles'));
    }

    public function search(Request $request)
    {
        $query = User::query();
    
        if ($request->has('search') && $request->search !== '') {
            $query->where('username', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%')
                ->orWhere('lastname', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }
    
        $users = $query->get();
        
        return view('admin.members_search_results', compact('users'));
    }
    


    
    public function Editer($user_id){
        $famille = User::where('family_id', $user_id)->get();
        return view('admin.modals.familleMembers', compact('famille'))->with('user', auth()->user());

    }  

    public function editUsermodal($user_id){
        $n_users = User::find($user_id);
        $roles = Role::all();
        return view('admin.modals.editUser', compact('n_users','roles'))->with('user', auth()->user());
    }

    public function message_general(Request $request){
        $id = $request->input('setting_id'); 
        $value = $request->input('setting_value');
    
        $systemSetting = SystemSetting::findOrFail($id);
        $systemSetting->value = $value;
        $systemSetting->save();
    
        return response()->json(['message' => 'Valeur mise à jour avec succès']);
    }
 

    public function addUser(AddUserform $request){
        $validateData = $request->validated();
        $addUser = new User();
        $biggest_family_id = User::max('family_id');
        $addUser->username = $validateData['email'];
        $addUser->name = $validateData['name'];
        $addUser->lastname = $validateData['lastname'];
        $addUser->email = $validateData['email'];
        $addUser->password = bcrypt($validateData['password']);
        $addUser->phone = $validateData['phone'];
        $addUser->profession = $validateData['profession'];
        $addUser->gender = $validateData['gender'];
        $addUser->birthdate = $validateData['birthdate'];
        $addUser->nationality = $validateData['nationality'];
        $addUser->address = $validateData['address'];
        $addUser->zip = $validateData['zip'];
        $addUser->city = $validateData['city'];
        $addUser->country = $validateData['country'];
        $addUser->family_id = $biggest_family_id + 1;
        $addUser->role = $validateData['role'];
        $addUser->save();
        return redirect()->route('utilisateurs.members')->with('success', 'Le User a été ajouté avec succès');
        
        
    }

    public function mdpUniverselmodal($user_id){
        $n_users = User::find($user_id);

        return view('admin.modals.mdpUniversel', compact('n_users'))->with('user', auth()->user());
    }

    public function editUser(Request $request, $user_id)
    {
    $user = User::find($user_id);
    $validatedData = $request->validate( [
        'username' => 'nullable|string|max:255',
        'name' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
        'lastname' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
        'email' => [ 'nullable','string', 'email', 'max:255'],
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

    if(!empty($request->password)){
        $request->merge(['password' => Hash::make($request->password)]);
   }
   else{
       unset($request['password']);
   }

        $user->update($request->all());
        return redirect()->back()->with('success', 'Le profil a été modifié avec succès');
    
    }

    public function DeleteUser($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->route('utilisateurs.members')->with('success', 'Le profil a été supprimé avec succès');
    }

    public function mdpUniversel($id){
        $user = User::find($id);
        $user->update([
            'password' => bcrypt('concordia')
        ]);
        $email = $user->email;

        Mail::send('emails.mdpUniversel', ['users' => $user, 'email' => $user->email], function (Message $message) use ($email) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($email);
            $message->subject('Reinitialisation du Mot de Passe');
        });

        return redirect()->back()->with('success', 'Le mot de passe de '.$user->lastname.' '.$user->name.' a été réinitialisé avec succès');
    }

    public function familleMembers($user_id){
        $famille = User::where('family_id', $user_id)->get();
        return view('admin.modals.familleMembers', compact('famille'))->with('user', auth()->user());
    }

    public function DeleteUsermodal($id){
        $n_users = User::find($id);
        return view('admin.modals.deleteUser', compact('n_users'))->with('user', auth()->user());
    }
    
}

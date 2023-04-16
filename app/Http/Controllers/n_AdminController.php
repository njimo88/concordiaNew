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

require_once(app_path().'/fonction.php');





class n_AdminController extends Controller
{
    public function index()

    {
        if (auth()->user()->role == 40 || auth()->user()->role == 30){
            $saison_actu = saison_active() ;

            $shop_article_lesson =  shop_article_1::select('shop_article_1.teacher', 'shop_article.title','shop_article_1.id_shop_article','shop_article.stock_actuel','shop_article.stock_ini')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison', $saison_actu)->get();
            return view('Statistiques/Home_stat_teacher',compact('shop_article_lesson'))->with('user', auth()->user());

        }else{
            return view('admin.index')->with('user', auth()->user());
        }


    }

    public function members()
    {
        $roles = Role::all();

        $n_users = User::orderBy('name', 'asc')
                ->select('user_id', 'username', 'name', 'lastname', 'birthdate', 'phone','family_id')
                ->get();
        
        return view('admin.members', compact('n_users','roles'));
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
        'name' => ['required', 'alpha', 'max:255'],
        'lastname' => ['required', 'alpha', 'max:255'],
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

    if(!empty($request->password)){
        $request->merge(['password' => Hash::make($request->password)]);
   }
   else{
       unset($request['password']);
   }

        $user->update($request->all());
        return redirect()->route('utilisateurs.members')->with('success',$request->lastname.' '.$request->name. ' a été mis à jour avec succès');
    
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

        return redirect()->route('utilisateurs.members')->with('success','Le mot de pass de '.$user->lastname.' '.$user->name.' a été réinitialisé avec succès');
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

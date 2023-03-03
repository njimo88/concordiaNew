<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserform;
use App\Http\Requests\AddEnfantform;
use Illuminate\Support\Facades\Auth;
use App\models\bills;
use App\Models\old_bills;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;




class UsersController extends Controller
{
    
public function editdata(){
    // Retrieve all unique email addresses from the "users" table
    $emails = DB::table('users')
        ->select('email')
        ->distinct()
        ->get();

    // Loop through each email address
    foreach ($emails as $email) {
        // Retrieve the user with that email
        $user = DB::table('users')
            ->where('email', $email->email)
            ->where('role', '<', 20)
            ->first();

    // Check if the user meets the condition
    if ($user) {
        // Update the user's username to their email
        DB::table('users')
            ->where('user_id', $user->user_id)
            ->update(['username' => $user->email]);
    }
    }
    dd($emails);
    dd('done');
}

public function panier($id)
{
    // Récupérer l'utilisateur correspondant à l'id
    $users = User::find($id);
    
    // Récupérer tous les paniers associés à l'utilisateur avec les informations de l'article correspondant
    $paniers = DB::table('basket')
                ->join('shop_article', 'basket.ref', '=', 'shop_article.id_shop_article')
                ->select('basket.qte', 'shop_article.title', 'shop_article.image', 'shop_article.price', 'shop_article.ref as reff')
                ->get();
    // Retourner la vue avec les données récupérées
    return view('users.panier', compact('paniers','users'))->with('user', auth()->user());
}



    

    /*Update users*/
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => [ 'alpha', 'max:255'],
            'lastname' => ['alpha', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->user()->user_id],
            'phone' =>  ['required', 'regex:/^0[0-9]{9}$/'],
            'profession' => 'alpha|max:191',
            'birthdate' => 'date',
            'nationality' => 'required',      
        ]);

        auth()->user()->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'profession' => $request->profession,
            'birthdate' => $request->birthdate,
            'nationality'=> $request->nationality,
        ]);

        return redirect()->route('users.edit-profil')->with('success', 'Votre profil a été mis à jour avec succès');
    }

    public function edit(){
        return view('users.edit-profil')->with('user', auth()->user());
    }

    public function authortze()
    {
        return true;
    }


    public function family(){
        $n_users = User::where('family_id', Auth::user()->family_id)->get();
            if (is_null($n_users)) {
                return view('users.family')->with('user', auth()->user());
            } else {
                return view('users.family',compact('n_users'))->with('user', auth()->user());
            }
    }


public function addMember(AddUserform $request){
    $validateData = $request->validated();
    $addMember = new User();

    $addMember->name = $validateData['name'];
    $addMember->lastname = $validateData['lastname'];
    $addMember->email = $validateData['email'];
    $addMember->password = bcrypt($validateData['password']);
    $addMember->phone = $validateData['phone'];
    $addMember->profession = $validateData['profession'];
    $addMember->gender = $validateData['gender'];
    $addMember->birthdate = $validateData['birthdate'];
    $addMember->nationality = $validateData['nationality'];
    $addMember->address = $validateData['address'];
    $addMember->zip = $validateData['zip'];
    $addMember->city = $validateData['city'];
    $addMember->country = $validateData['country'];
    $addMember->family_id = auth()->user()->family_id;
    $addMember->family_level = "parent";
    $addMember->save();
    return redirect()->route('users.family')->with('success', 'Le parent a été ajouté avec succès');
}

public function addEnfant(AddEnfantform $request){
    $validateData = $request->validated();
    $addMember = new User();

    $addMember->name = $validateData['name'];
    $addMember->lastname = $validateData['lastname'];
    $addMember->email = $validateData['email'];
    $addMember->password = bcrypt($validateData['password']);
    $addMember->phone = $validateData['phone'];
    $addMember->profession = $validateData['profession'];
    $addMember->gender = $validateData['gender'];
    $addMember->birthdate = $validateData['birthdate'];
    $addMember->nationality = $validateData['nationality'];
    $addMember->address = $validateData['address'];
    $addMember->zip = $validateData['zip'];
    $addMember->city = $validateData['city'];
    $addMember->country = $validateData['country'];
    $addMember->family_id = auth()->user()->family_id;
    $addMember->family_level = "child";
    $addMember->save();
    return redirect()->route('users.family')->with('success', 'L\'enfant a été ajouté avec succès');
}

public function editFamille(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $validatedData = $request->validate([
            'name' => ['required', 'alpha', 'max:255'],
            'lastname' => ['required', 'alpha', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')->where(function($query) use ($user) {
                return $query->where('family_id', '!=', $user->family_id);
            })],
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'profession' => 'string|max:191',
            'birthdate' => 'required|date|before:today',
            'address' => 'required',
            'zip' => 'required|numeric',
            'city' => 'required',
            'nationality' => 'required',
        ]
        , $messages = [
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
        'image.image' => "Le fichier doit être une image.",
        'image.mimes' => "Le fichier doit être une image de type : jpeg, png, jpg, gif, svg.",
        'image.max' => "Le fichier doit être inférieur à 2MB.",
        
        
    ]);
    if(!empty($request->password)){
        $request->merge(['password' => Hash::make($request->password)]);
   }
   else{
       unset($request['password']);
   }
   if ($request->hasFile('image')) {
    $path = $request->file('image')->store('public\uploads\users_an\froze');
    $request->merge(['image' => $path]);
}

$user->update($request->all());
return redirect()->route('users.family')->with('success', $request->name . " " . $request->lastname . ' a été mis à jour avec succès');



}


    public function detailsUser($user_id){
        $user = User::find($user_id);
        return view('users.modals.detailsUser', compact('user'));
    }



public function facture(){
    $user = Auth::user();

$bill = DB::table('bills')
    ->join('bills_status', 'bills.status', '=', 'bills_status.id')
    ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
    ->where('bills.user_id', $user->user_id)
    ->select('bills.*', 'bills_status.image_status as image_status', 'bills_status.row_color as row_color', 'bills_payment_method.image as image')
    ->union(
        DB::table('old_bills')
        ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
        ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
        ->where('old_bills.user_id', $user->user_id)
        ->select('old_bills.*', 'bills_status.image_status as image_status', 'bills_status.row_color as row_color', 'bills_payment_method.image as image')
    )->orderBy('date_bill', 'desc')
    ->get();
    return view('users.facture',compact('bill'))->with('user', auth()->user());
}

public function deleteFacture($id){
    $bills = bills::find($id);
    if($bills == null){
        $bills = old_bills::find($id);
    }
    $bills->delete();
    return redirect()->route('users.FactureUser')->with('success', 'La facture a été supprimée avec succès');
 

}

public function showBill($id){
    $bill = $bill = DB::table('bills')
    ->join('users', 'bills.user_id', '=', 'users.user_id')
    ->where('bills.id', $id)
    ->first();
    if($bill == null){
        $bill = $bill = DB::table('old_bills')
        ->join('users', 'old_bills.user_id', '=', 'users.user_id')
        ->where('old_bills.id', $id)
        ->first();
    }
    return view('users.showBill',compact('bill'))->with('user', auth()->user());}
}
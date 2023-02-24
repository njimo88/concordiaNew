<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [

            'name' => ['required', 'alpha', 'max:255'],
            'lastname' => ['required', 'alpha', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' =>  ['required', 'regex:/^0[0-9]{9}$/'],
            'profession' => 'required|string|max:191',
            'gender' => 'required|in:male,female',
            'birthdate' => 'required|date|before:today',
            'nationality' => 'required',
            'address' => 'required',
            'zip' => ['required', 'regex:/^\d{5}(?:[-\s]\d{4})?$/'],
            'city' => 'required|alpha',
            'country' => 'required',
            $messages = [
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
            ],
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $biggest_family_id = User::max('family_id');
        return User::create([
            'username'=> $data['email'],
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'profession' => $data['profession'],
            'gender'=>$data['gender'],
            'birthdate' => $data['birthdate'],
            'nationality' => $data['nationality'],
            'address' => $data['address'],
            'zip' => $data['zip'],
            'city' => $data['city'],
            'country' => $data['country'],
            'family_id' => $biggest_family_id+1,
        ]);
    }

 
}

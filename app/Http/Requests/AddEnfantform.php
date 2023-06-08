<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddEnfantform extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'alpha', 'max:255'],
            'lastname' => ['required', 'alpha', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
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
        ];
    }

    public function messages()
    {
        return [
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

        ];  
}
}
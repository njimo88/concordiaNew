<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMemberform extends FormRequest
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
            'nameMem' => ['required', 'alpha', 'max:255'],
            'lastnameMem' => ['required', 'alpha', 'max:255'],
            'emailMem' => ['required', 'string', 'email', 'max:255'],
            'passwordMem' => ['required', 'string', 'min:8', 'confirmed'],
            'phoneMem' =>  ['required', 'regex:/^0[0-9]{9}$/'],
            'professionMem' => 'required|alpha|max:191',
            'genderMem' => 'required|in:male,female',
            'birthdateMem' => 'required|date|before:today',
            'nationalityMem' => 'required',
            'addressMem' => 'required',
            'zipMem' => ['required', 'regex:/^\d{5}(?:[-\s]\d{4})?$/'],
            'cityMem' => 'required|alpha',
            'countryMem' => 'required', 
        ];
    }

    public function messages()
    {
        return [
            'nameMem.required' => "Le champ nom est requis.",
            'nameMem.alpha' => "Le nom doit être une chaîne de caractères.",
            'lastnameMem.required' => "Le champ prénom est requis.",
            'lastnameMem.alpha' => "Le prénom doit être une chaîne de caractères.",
            'emailMem.required' => 'Le champ :attribute est requis.',
            'emailMem' => "Le format de l'adresse e-mail est invalide.",
            'emailMem.unique' => "L'adresse e-mail est déjà utilisée.",
            'passwordMem.required' => "Le champ mot de passe est requis.",
            'passwordMem.min' => "Le mot de passe doit contenir au moins 8 caractères.",
            'passwordMem.confirmed' => "La confirmation du mot de passe ne correspond pas.",
            'phoneMem.required' => "Le champ numéro de téléphone  est requis.",
            'phoneMem.regex' => "Le format du numéro de téléphone est invalide.",
            'genderMem.required' => "Le champ sexe est requis.",
            'birthdateMem.required' => 'La date de naissance est requise',
            'birthdate.date' => 'Format de date non valide',
            'birthdateMem.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
            'professionMem.alpha' => "La profession doit être une chaîne de caractères.",
            'addressMem.required' => "Le champ address est requis.",
            'zipMem.required' => "Le champ code postal est requis.",
            'zipMem.regex' => "Le code postal doit être au format 12345 ou 12345-1234.",
            'cityMem.required' => "Le champ ville est requis.",
            'cityMem.alpha' => "La ville doit être une chaîne de caractères.",
            'countryMem.required' => "Le champ pays est requis.",

        ];  
}
}
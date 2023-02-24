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
            'profession' => 'required|alpha|max:191',
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
            'name.required' => 'Le nom est requis',
            'name.alpha' => 'Le nom ne peut contenir que des lettres',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'lastname.required' => 'Le prénom est requis',
            'lastname.alpha' => 'Le prénom ne peut contenir que des lettres',
            'lastname.max' => 'Le prénom ne peut pas dépasser 255 caractères',
            'email.required' => 'L\'email est requis',
            'email.string' => 'L\'email doit être une chaîne de caractères',
            'email.email' => 'L\'email doit être une adresse email valide',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères',
            'password.required' => 'Le mot de passe est requis',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'phone.required' => 'Le numéro de téléphone est requis',
            'phone.regex' => 'Le numéro de téléphone doit être valide',
            'profession.required' => 'La profession est requise',
            'profession.alpha' => 'La profession ne peut contenir que des lettres',
            'profession.max' => 'La profession ne peut pas dépasser 191 caractères',
        ];  
}
}
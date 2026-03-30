<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:applications,email',
            'role' => 'required|in:developer,designer',
            'motivation' => 'required|string',
            'portfolio' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048', // Limite à 2MB
        ];
    }

    /**
     * Message d'erreur personnalisés pour les validations.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {        return [
            'name.required' => 'Vueillez entrer votre nom.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Vous avez déjà soumis une candidature avec cet email ! Vueillez utiliser une adresse email différente ou contacter le support si vous pensez que c\'est une erreur.',
            'role.required' => 'Vueillez choisir une option.',
            'role.in' => 'Le rôle doit être soit "developer" soit "designer".',
            'motivation.required' => 'Vueillez saisir votre message de motivation.',
            'cv.required' => 'Vous devez fournir un CV.',
            'cv.file' => 'Le CV doit être un fichier.',
            'cv.mimes' => 'Le CV doit être un fichier de type : pdf, doc ou docx.',
            'cv.max' => 'Fichier trop volumineux. Le CV ne doit pas dépasser 2MB.',
        ];
    }
}
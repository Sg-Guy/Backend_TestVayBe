<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
        $rules= [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255|unique:applications,email',
            'role' => 'required|in:developer,designer',
            'motivation' => 'required|string',
            'portfolio' => 'nullable|url',
            'cv' => 'required|file|mimes:pdf|max:2048', // Limite à 2MB
        ];
        return $rules ;
    }

    
    //messages de validation personnalisées
    public function messages()
    {
               
        return [
            'name.required' => 'Veuillez entrer votre nom.',

            'name.string' => 'Le nom doit être une chaîne de caractères.',

            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'email.required' => 'L\'email est requis.',

            'email.email' => 'Veuillez entrer une adresse email valide.',

            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',

            'email.unique' => 'Vous avez déjà soumis une candidature avec cet email ! Veuillez utiliser une adresse email différente ou contacter le support si vous pensez que c\'est une erreur.',
            
            'role.required' => 'Vueillez choisir une option.',

            'role.in' => 'Le rôle doit être soit developer soit designer.',

            'motivation.required' => 'Veuillez saisir votre message de motivation.',

            'portfolio.url' => 'Veuillez entrer une URL valide.',

            'cv.required' => 'Vous devez fournir un CV.',

            'cv.file' => 'Le CV doit être un fichier.',

            'cv.mimes' => 'Le CV doit être un fichier de type : pdf.',

            'cv.max' => 'Fichier trop volumineux. Le CV ne doit pas dépasser 2MB.',
        ];
    }


    //Envoie du json en cas d'erreur de validation
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => 'Validation échouée.',
            'errors' => $validator->errors()
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
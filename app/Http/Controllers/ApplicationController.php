<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\ApplicationModel;
use Exception;
use Illuminate\Http\Request;

use function Laravel\Prompts\clear;

class ApplicationController extends Controller
{

    //Recuperation de toutes les candidatures
    public function index()
    {
        $applications = ApplicationModel::all();
        return response()->json($applications);
    }

    // Enregistrement d'une nouvelle candidature
    public function store(ApplicationRequest $request)
    {


        $keywords_dev = ['frontend', 'backend', 'uml', 'fullstack', 'api', 'api rest', 'web', 'mobile', 'react', 'angular', 'vue', 'laravel', 'nodejs', 'django'];
        $keywords_designer = ['ui/ux', 'graphic', 'web', 'mobile', 'branding', 'figma'];

        $application = new ApplicationModel();


        // Traitement de l'upload du CV
        if ($request->hasFile('cv')) {
            $path_cv = $request->file('cv')->store('fichiers_cv', 'public'); // Stockage du cv dans le dossier "storage/app/public/fichiers_cv"
        } else {
            return response()->json(['error' => 'Vous devez fournir un CV.'], 400);
        }


        try {
            $application->name = $request->input('name');
            $application->email = $request->input('email');
            $application->role = $request->input('role');
            $application->motivation = $request->input('motivation');
            $application->portfolio = $request->input('portfolio', null);
            $application->cv = $path_cv; // Enregistrement du chemin du CV



            //J'envisage créer une table role apres//

            if ($application->role === 'developer') {
                $application->score = $this->logiqueCheck($keywords_dev, $application->motivation, $application->portfolio, $application->email);
            } elseif ($application->role === 'designer') {
                $application->score = $this->logiqueCheck($keywords_designer, $application->motivation, $application->portfolio, $application->email);
            } else {
                $application->score = 0; // Si le rôle n'est pas reconnu, on attribue un score de 0
            }

            // Création de la candidature en base
            $application->save();
        } catch (Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors du traitement de votre candidature. Veuillez réessayer plus tard.'], 500);
        }



        return response()->json(
            [
                'message' => 'Votre candidature a été soumise avec succès. !',
                'application' => $application
            ],
            201
        );
    }



    // Fonction de logique de scoring

    public function logiqueCheck(array $keywords, string $motivation, $portfolio, $email): int
    {
        $foundKeywords = [];
        $score = 10;


        foreach ($keywords as $keyword) {
            if (str_contains(strtolower($motivation), $keyword)) {
                $foundKeywords[] = $keyword; // Stocke les mots-clés trouvés
            }
        }

        if ($foundKeywords != null) {
            foreach ($foundKeywords as $found) {
                $score += 1; // Ajoute 1 points pour chaque mot-clé trouvé
            }
        } else {
            $score = $score; // Aucun mot-clé trouvé, score de 10
        }

        if ($portfolio != null) {
            $score += 1; // Ajoute 1 point si un portfolio est fourni
        }
        if ($email) {
            $score += 1; // Ajoute 1 point si un email est fourni
        }

        return $score;
    }
}

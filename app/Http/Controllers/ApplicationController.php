<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\ApplicationModel;
use Exception;
use Illuminate\Http\Request;


use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Candidatures", 
    description: "Endpoints pour gérer les candidatures"
)]
class ApplicationController extends Controller
{

    //Recuperation de toutes les candidatures


    // Documentation 
    #[OA\Get(
        path: "/api/applications",
        tags: ["Candidatures"],
        security: [["sanctum" => []]],
        summary: "Récupérer toutes les candidatures",
        description: "Permet de récupérer la liste de toutes les candidatures soumises",
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des candidatures récupérée avec succès",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "SABO Guillaume"),
                            new OA\Property(property: "email", type: "string", example: "guillaume@gmail.com"),
                            new OA\Property(property: "rôle", type: "string", example: "Développeur"),
                            new OA\Property(property: "motivation", type: "string", example: "ma motivation pour ce poste est..."),
                            new OA\Property(property: "portfolio", type: "string", example: "https://portfolio.com"),
                            new OA\Property(property: "cv", type: "string", example: "cv.pdf")
                    ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: "Authentification requise"
            ),
            new OA\Response(
                response: 500,
                description: "Une erreur est survenue lors du traitement de la requête"
            )
        ]

    )]

    public function index()
    {
        /*if (!auth()->check()) {
            return response()->json(['message' => 'Authentification requise pour accéder à cette ressource.'], 401);
        }*/

         $applications = ApplicationModel::orderBy('created_at', 'desc')->get(); 
        return response()->json($applications);
    }

    /* 
    
        Enregistrement d'une nouvelle candidature

        Doumentation
    */

    #[OA\Post(
        path: "/api/applications",
        tags: ["Candidatures"],
        security: [["sanctum" => []]],
        summary: "Soumettre une nouvelle candidature",
        description: "Permet de soumettre une nouvelle candidature",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                    mediaType: "multipart/form-data",
                   schema: new OA\Schema(
                    required: ["name", "email", "role", "motivation", "cv"],
                    type: "object",
                         properties: [
                        new OA\Property(property: "name", type: "string", example: "SABO Guillaume"),
                        new OA\Property(property: "email", type: "string", example: "guillaume@gmail.com"),
                        new OA\Property(property: "role", type: "string", example: "Développeur"),
                        new OA\Property(property: "motivation", type: "string", example: "ma motivation pour ce poste est..."),
                        new OA\Property(property: "portfolio", type: "string", example: "https://portfolio.com"),
                        new OA\Property(property: "cv", type: "file", example: "cv.pdf")
                    ]
                    )
                )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Candidature soumise avec succès",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Votre candidature a été soumise avec succès. !"),
                        new OA\Property(property: "application", example:'')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Données invalides"
            ),
            new OA\Response(
                response: 401,
                description: "Authentification requise"
            ),
            new OA\Response(
                response : 500,
                description : "Une erreur est survenue lors du traitement de la requête"
            )
        ]
    )]

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

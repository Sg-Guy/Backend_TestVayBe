# Test Technique - Developpeur Full-Stack (Vaybe) : Backend

Ce projet constitue l'API REST de l'application de gestion de candidatures pour Vaybe.

## Structure du projet

- Ce depot contient l'API developpee avec le framework Laravel.

## Architecture
app/
├── Http/
│   ├── Controllers/
│   │   └── ApplicationController.php    # Logique de reception des requetes 
│   ├── Requests/
│   │   └── ApplicationRequest.php  # Validation des donnees (nom, email ...) 
├── Models/
│   └── ApplicationModel.php                  # Definition des champs (nom, role, score)
├── Services/
│   └── ScoringService.php               # Logique de calcul du score (Isolee du controller) 
└── Providers/
└── OpenApi.php                           # Pour Swagger
routes/
└── api.php                              # Definition des endpoints (GET/POST /applications) 

## Installation et Lancement du Backend

### Etape 1 : Cloner le depot
git clone https://github.com/Sg-Guy/Backend_TestVayBe

cd Backend_TestVayBe

### Etape 2 : Configuration et Lancement
composer install

cp .env.example .env

(configurer ce fichier .env pour communiquer avec votre base de données)

php artisan migrate:fresh --seed      ***Important pour Créer l'admin en base de données***

php artisan serve

L'API sera disponible sur http://127.0.0.1:8000.

### Faire les premiers test
 Cette Api a été documenté avec **swagger** pour faciliter les tests .
 Vous pouvez donc réalisé un test  ici : http://127.0.0.1:8000/api/documentation sans toutes fois lancerr insomnia ou postman. Pour utliser les endpoints admin , assurez vous de vous connecter , de récupérer le token et de le mettre dans la partir Authorize sur l'interface swagger. example : Bearer 2|zvA7a6rtByEg2Xd2F0vSSaOOu5njEF1bqMpFhFAE6d5084f4


## Fonctionnalites et API

1. Endpoint POST /api/applications : Permet de soumettre une candidature avec nom, email, role, motivation, portfolio et CV.

2. Endpoint GET /api/applications : Retourne la liste des candidatures soumises.

3. Systeme de Scoring : Calcul automatique basé sur la completude du profil (email valide, portfolio renseigne, mots-cles dans la motivation).Pour cette logique , un tableau de mots clés a été foruni de le controller pour chaque post de la candidature. Quand le candidat soumet sa candidature , le système vérifie si chacun des mots de la liste correspondant au poste sont dans le message de motivation et à chaque qu'un mot remplisse cette condition , le score est incréménté de 1. Si le candidat a fourni un portfolio (url valide) , le score augmente de 1. Tout candidat ayant soumis avec succès sa canditure a droit au 1 point lié à la validation de mail puisque cette validation est gérée dans le FormRequest.

4. Gestion d'erreurs : Les erreurs sont biens gérées dans le FormResquest

5. Documentation: Cette Api a été documentée avec **swagger** pour faciliter tests. Pour accéder à l'interface swagger , cliquez sur : http://127.0.0.1:8000/api/documentation

## Choix techniques

- Framework : PHP Laravel.

- Validation : Validation stricte des champs obligatoires et des formats de fichiers via un FormRequest.*

- Gestion des erreurs : Reponses claires pour faciliter l'experience utilisateur cote frontend.

- Scoring : Logique de calcul integree cote backend pour assurer l'integrite des scores.
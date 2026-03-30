<?php

namespace App;

use OpenApi\Attributes as OA;

//Informations de L'API
#[OA\Info(
    version: "1.0.0",
    title: "TestVayBe API",
    description: "Documentation de l'API TestVayBe"
)]


//Infrmations server
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Serveur local"
)]

//Pour l'authentification
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]

class OpenApi {}
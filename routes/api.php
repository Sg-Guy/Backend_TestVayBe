<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;



//login 
Route::post('/user/login', [UserController::class, 'login']);

// Endpoints pour les candidatures
Route::prefix("/applications")->group(function () {

    // Route pour créer une nouvelle candidature
    Route::post('/', [ApplicationController::class, 'store']);


    // Route pour récupérer toutes les candidatures
    Route::get('/', [ApplicationController::class, 'index']) ; //->middleware(['auth:sanctum']); 
});

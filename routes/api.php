<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Endpoints pour les candidatures

// Route pour créer une nouvelle candidature
Route::post('/applications', [ApplicationController::class, 'store']);


// Route pour récupérer toutes les candidatures
Route::get ('/applications', [ApplicationController::class, 'index']); //->middleware(['auth:sanctum']); 
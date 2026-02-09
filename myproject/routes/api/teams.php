<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;

Route::get('/teams', [TeamController::class, 'index']);

Route::post('/teams', [TeamController::class, 'store']);       
Route::get('/teams/{id}', [TeamController::class, 'show']);    
Route::put('/teams/{id}', [TeamController::class, 'update']);  
Route::delete('/teams/{id}', [TeamController::class, 'destroy']); 
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;

Route::post('/workspaces', [WorkspaceController::class, 'store']);
Route::get('/workspaces', [WorkspaceController::class, 'index']);
Route::get('/workspaces/{id}', [WorkspaceController::class, 'show']);
Route::put('/workspaces/{id}', [WorkspaceController::class, 'update']);
Route::delete('/workspaces/{id}', [WorkspaceController::class, 'destroy']);
Route::post('/workspaces/add-member', [WorkspaceController::class, 'addMember']);
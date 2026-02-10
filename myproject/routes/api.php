<?php

use Illuminate\Support\Facades\Route;
use App\Models\PersonalAccessToken; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;

require __DIR__ . '/api/auth.php';

Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])
->name('verification.verify');
Route::post('/otp/send', [AuthController::class, 'sendOtp']);
Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['custom_auth'])->group(function () {
    
    require __DIR__ . '/api/workspaces.php';
    require __DIR__ . '/api/channels.php';
    require __DIR__ . '/api/messages.php';
    require __DIR__ . '/api/teams.php'; 
    
}
);
Route::get('/files/{path}', [FileController::class, 'download'])->where('path', '.*');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
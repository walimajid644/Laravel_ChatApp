<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChannelController;

Route::post('/channels', [ChannelController::class, 'store']);
Route::get('/channels', [ChannelController::class, 'index']);
Route::get('/channels/{id}', [ChannelController::class, 'show']);
Route::put('/channels/{id}', [ChannelController::class, 'update']);
Route::delete('/channels/{id}', [ChannelController::class, 'destroy']);

Route::post('/channels/{id}/members', [ChannelController::class, 'addMember']);
Route::post('/channels/{id}/remove-member', [ChannelController::class, 'removeMember']);
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/mongo-test', function () {
    DB::collection('test')->insert([
        'status' => 'MongoDB Connected',
        'time' => now(),
    ]);

    return 'MongoDB is working!';
});


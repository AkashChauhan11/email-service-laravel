<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ping', function () {
    return response()->json([
        'success' => true,
        'message' => 'Web route is working',
        'timestamp' => now()->toIso8601String(),
    ]);
});

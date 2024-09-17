<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/UserRoutes.php';
require __DIR__ . '/api/CarRoutes.php';

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

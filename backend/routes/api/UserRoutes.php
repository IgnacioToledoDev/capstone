<?php

use App\Http\Controllers\APIv1Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('recovery', [UserController::class, 'recoveryPassword']);
    Route::post('reset', [UserController::class, 'resetPassword']);
    Route::post('logout', [UserController::class, 'logout']);
});

Route::prefix('/jwt/mechanic')->group(function () {
    Route::post('/{mechanicId}/setScore', [UserController::class, 'setMechanicScore']);
    Route::get('/all', [UserController::class, 'getAllMechanics']);
})->middleware('auth:api');


Route::prefix('/jwt/client')->group(function () {
    Route::post('/register', [UserController::class, 'registerClient'])->middleware('auth:api');
    Route::get('/information', [UserController::class, 'getUserInformation']);
    Route::get('/{rut}/find', [UserController::class, 'getUserByRut']);
})->middleware('auth:api');

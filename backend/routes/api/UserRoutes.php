<?php

use App\Http\Controllers\APIv1Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('recovery', [UserController::class, 'recoveryPassword']);
    Route::post('reset', [UserController::class, 'resetPassword']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('client/register', [UserController::class, 'registerClient'])->middleware('auth:api');
});

Route::prefix('mechanic')->group(function () {
    Route::post('{mechanicId}/setScore/', [UserController::class, 'loginClient']);
})->middleware('auth:api');

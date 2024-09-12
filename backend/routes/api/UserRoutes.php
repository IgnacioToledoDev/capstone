<?php

use App\Http\Controllers\APIv1Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('recovery', [UserController::class, 'recoveryPassword']);
    Route::post('reset', [UserController::class, 'resetPassword']);
});

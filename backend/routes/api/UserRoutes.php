<?php

use App\Http\Controllers\APIv1Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->group(function () {
    Route::post('login', [UserController::class, 'login']);
});

<?php

use App\Http\Controllers\APIv1Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/services')->middleware('auth:api')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
});

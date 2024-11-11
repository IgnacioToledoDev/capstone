<?php

use App\Http\Controllers\APIv1Controllers\CarController;
use Illuminate\Support\Facades\Route;


Route::prefix('/jwt/cars')->middleware('auth:api')->group(function () {
    Route::get('/', [CarController::class, 'index']);
    Route::post('/create', [CarController::class, 'store']);
    Route::get('/{patent}', [CarController::class, 'show']);
    Route::get('/{userId}/all', [CarController::class, 'getCarsByUserId']);
});

<?php

use App\Http\Controllers\APIv1Controllers\CarController;
use Illuminate\Support\Facades\Route;


Route::prefix('/jwt/cars')->middleware('auth:api')->group(function () {
    Route::post('/create', [CarController::class, 'store']);
});

<?php

use App\Http\Controllers\APIv1Controllers\CarBrandController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/cars')->middleware('auth:api')->group(function () {
    Route::get('/brands/all ', [CarBrandController::class, 'index']);
});

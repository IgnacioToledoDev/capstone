<?php

use App\Http\Controllers\APIv1Controllers\CarModelController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/cars')->middleware('auth:api')->group(function () {
    Route::get('/models/all ', [CarModelController::class, 'getAllModels']);
    Route::get('/models/all/{brandId} ', [CarModelController::class, 'getModelsByBrandId']);
});

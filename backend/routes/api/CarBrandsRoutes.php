<?php

use App\Http\Controllers\APIv1Controllers\CarBrandController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/brands')->middleware('auth:api')->group(function () {
    Route::get('/', [CarBrandController::class, 'index']);
});

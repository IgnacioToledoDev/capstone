<?php


use App\Http\Controllers\APIv1Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;


Route::prefix('/jwt/maintenance')->middleware('auth:api')->group(function () {
    Route::post('/create', [MaintenanceController::class, 'store']);
});

<?php


use App\Http\Controllers\APIv1Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;


Route::prefix('/jwt/maintenance')->middleware('auth:api')->group(function () {
    Route::post('/create', [MaintenanceController::class, 'store']);
    Route::get('/update/current-client', [MaintenanceController::class, 'updateCurrentClient']);
    Route::get('/calendar', [MaintenanceController::class, 'index']);
    Route::get('/historical', [MaintenanceController::class, 'getHistorical']);
});

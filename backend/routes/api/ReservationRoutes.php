<?php

use App\Http\Controllers\APIv1Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/')->middleware('auth:api')->group(function () {
    Route::post('/reservation', [ReservationController::class, 'createReservation']);
});

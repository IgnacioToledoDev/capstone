<?php

use App\Http\Controllers\APIv1Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/reservation')->middleware('auth:api')->group(function () {
    Route::post('/create', [ReservationController::class, 'createReservation']);
    Route::get('/{mechanicId}/reservations', [ReservationController::class, 'getAllReservation']);
    Route::patch('/{reservationId}/approve', [ReservationController::class, 'approvedReservation']);
});

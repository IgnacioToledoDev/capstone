<?php

use App\Http\Controllers\APIv1Controllers\ContactTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/contactTypes')->middleware('auth:api')->group(function () {
    Route::get('/', [ContactTypeController::class, 'getAllContactTypes']);
});

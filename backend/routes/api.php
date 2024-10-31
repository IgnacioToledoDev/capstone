<?php

use App\Http\Controllers\APIv1Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/UserRoutes.php';
require __DIR__ . '/api/CarRoutes.php';
require __DIR__ . '/api/CarBrandsRoutes.php';
require __DIR__ . '/api/ServiceRoutes.php';
require __DIR__ . '/api/MaintenanceRoutes.php';
require __DIR__ . '/api/CarModelsRoutes.php';
require __DIR__ . '/api/QuotationRoutes.php';
require __DIR__ . '/api/ReservationRoutes.php';


Route::get('login', [UserController::class, 'login'])->name('login');

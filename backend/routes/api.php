<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/UserRoutes.php';
require __DIR__ . '/api/CarRoutes.php';
require __DIR__ . '/api/CarBrandsRoutes.php';
require __DIR__ . '/api/ServiceRoutes.php';
require __DIR__ . '/api/MaintenanceRoutes.php';


Route::get('login', [\App\Http\Controllers\APIv1Controllers\UserController::class, 'login'])->name('login');

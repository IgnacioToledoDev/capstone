<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/tenant/create', function () {
    return view('tenant.create');
});


Route::resource('tenants', TenantController::class);

<?php

use App\Http\Controllers\APIv1Controllers\QuotationController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jwt/quotations')->middleware('auth:api')->group(function () {
    Route::post('/create', [QuotationController::class, 'store']);
    Route::get('/{quotationId}', [QuotationController::class, 'show']);
    Route::get('/', [QuotationController::class, 'index']);
    Route::patch('/{quotationId}/approve', [QuotationController::class, 'approve']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SalesOrderController;
use App\Http\Controllers\Api\SoDtController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('sales-orders', SalesOrderController::class);

Route::prefix('sales-orders/{sales_order}')->group(function () {
    Route::get('details', [SoDtController::class, 'index']);
    Route::post('details', [SoDtController::class, 'store']);
    Route::get('details/{id}', [SoDtController::class, 'show']);
    Route::put('details/{id}', [SoDtController::class, 'update']);
    Route::delete('details/{id}', [SoDtController::class, 'destroy']);
});

<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('payments', PaymentController::class);
});
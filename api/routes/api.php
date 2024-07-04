<?php

use App\Http\Controllers\API\ExpensesController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:api')->group( function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('expenses', ExpensesController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::apiResource('users', UserController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});
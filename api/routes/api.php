<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\ExpensesController;

Route::controller(LoginRegisterController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:api')->group( function () {
    Route::post('/logout', [LoginRegisterController::class, 'logout']);

    Route::controller(ExpensesController::class)->group(function() {
        Route::post('/expenses', 'store');
        Route::post('/expenses/{id}', 'update');
        Route::delete('/expenses/{id}', 'destroy');
    });
});
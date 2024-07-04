<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:api')->group( function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(ExpensesController::class)->group(function() {
        Route::get('/expenses', 'index');
        Route::post('/expenses', 'store');
        Route::post('/expenses/{id}', 'update');
        Route::delete('/expenses/{id}', 'destroy');
    });

    Route::controller(UserController::class)->group(function() {
        Route::get('/users', 'index');
        Route::post('/users', 'store');
        Route::post('/users/{id}', 'update');
        Route::delete('/users/{id}', 'destroy');
    });
});
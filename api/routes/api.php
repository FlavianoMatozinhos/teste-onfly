<?php

use App\Http\Controllers\API\ExpensesController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;

Route::controller(LoginRegisterController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:api')->group( function () {
    Route::post('/logout', [LoginRegisterController::class, 'logout']);

    Route::apiResource('expenses', ExpensesController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::apiResource('users', UserController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
    // Route::apiResource(ExpensesController::class)->group(function() {
    //     Route::get('/expenses', 'index');
    //     Route::post('/expenses', 'store');
    //     Route::post('/expenses/{id}', 'update');
    //     Route::delete('/expenses/{id}', 'destroy');
    // });

    // Route::apiResource(UserController::class)->group(function() {
    //     Route::get('/users', 'index');
    //     Route::post('/users', 'store');
    //     Route::post('/users/{id}', 'update');
    //     Route::delete('/users/{id}', 'destroy');
    // });
});
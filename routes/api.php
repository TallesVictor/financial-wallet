<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/user', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/user/balance', 'getBalance');
        Route::get('/user/{user:id}', 'show');
    });

    Route::controller(TransactionController::class)->prefix('transaction')->group(function () {
        Route::post('/transfer', 'transfer');
        Route::post('/deposit', 'deposit');
        Route::post('{transaction:transaction_id}/reverse', 'reverse');
        Route::get('/list', 'list');
    });
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

Route::middleware('web')->post('/login', [AuthController::class, 'login']);


Route::post('/user', [UserController::class, 'store']);

Route::middleware('web')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
   

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

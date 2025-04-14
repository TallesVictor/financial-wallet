<?php

use App\Http\Middleware\SessionAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('users.create');
})->name('user.register');
Route::middleware(SessionAuthenticate::class)->group(function () {

    Route::prefix('/transactions')->group(function () {
        Route::get('/transfer', function () {
            return view('transactions.transfer');
        })->name('transactions.transfer');

        Route::get('/list', function () {
            return view('transactions.list');
        })->name('transactions.list');

        Route::get('/deposit', function () {
            return view('transactions.deposit');
        })->name('transactions.deposit');
    });

});
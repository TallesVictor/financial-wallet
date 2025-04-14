<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/create/user', function () {
    return view('users.create');
});

Route::middleware(['web'])->get('/transactions/transfer', function () {
    return view('transactions.transfer');
})->name('transactions.transfer');

Route::middleware(['web'])->get('/transactions/list', function () {
    return view('transactions.list');
})->name('transactions.list');

Route::middleware(['web'])->get('/transactions/deposit', function () {
    return view('transactions.deposit');
})->name('transactions.deposit');

// Route::get('/users', function () {
//     return view('users.index');
// });


<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/create/user', function () {
    return view('users.create');
});

Route::get('/users', function () {
    return view('users.index');
});


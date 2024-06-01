<?php

use App\Http\Controllers\UserControllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.auth.login');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/home', function(){
        return view('pages.dashboard');
    })->name('home');


    Route::resource('users', UserControllers::class);
});


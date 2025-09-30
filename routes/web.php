<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [UserController::class, 'loginpage'])->middleware('guest')->name('mylogin');
Route::get('login', [UserController::class, 'loginpage'])->middleware('guest');
Route::get('start', [UserController::class, 'signup'])->name('signup');


// Route::get('/', function () {
//     return Inertia::render('Auth/login');
// });

// require __DIR__.'/auth.php';

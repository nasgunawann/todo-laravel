<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// route untuk user yang sudah login
Route::middleware(['auth'])->group(function () {
    // logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // dashboard (sementara pakai welcome)
    Route::get('/dashboard', function () {
        return view('welcome');
    })->name('dashboard');
});

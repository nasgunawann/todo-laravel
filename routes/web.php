<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfilController;

// redirect root ke dashboard jika sudah login, ke login jika belum
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('dashboard');
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

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // todo resource routes
    Route::resource('todo', TodoController::class);

    // todo custom actions
    Route::post('todo/{todo}/toggle-selesai', [TodoController::class, 'toggleSelesai'])
        ->name('todo.toggle-selesai');
    Route::post('todo/{todo}/toggle-sematkan', [TodoController::class, 'toggleSematkan'])
        ->name('todo.toggle-sematkan');
    Route::post('todo/{todo}/arsipkan', [TodoController::class, 'arsipkan'])
        ->name('todo.arsipkan');
    Route::post('todo/arsipkan-massal', [TodoController::class, 'arsipkanMassal'])
        ->name('todo.arsipkan-massal');

    // kategori routes (hanya index, store, update, destroy)
    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // profil routes
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});

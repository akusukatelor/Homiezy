<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/hasil-pencarian', [SearchController::class, 'index'])->name('search');
Route::get('/detail/{type}/{slug}', [SearchController::class, 'detail'])->name('service.detail');
Route::get('/jadi-mitra', [MitraController::class, 'index'])->name('mitra');

Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Socialite Login
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-saya', [DashboardController::class, 'index'])->name('dashboard');

    // Wizard Pemilihan Paket (Premium, Basic, Standard)
    Route::get('/paket/{type}', function ($type) {
        $validTypes = ['premium', 'basic', 'standard-clean', 'standard-meal'];
        
        if (in_array($type, $validTypes)) {
            return view('paket.' . $type);
        }
        
        return view('paket-wizard', compact('type'));
    })->name('paket.wizard');

    // Form Pelengkap Data (Khusus login Google tanpa WhatsApp)
    Route::get('/lengkapi-profil', function() { 
        return view('auth.complete-profile'); 
    })->name('complete.profile');
    
    Route::post('/lengkapi-profil', [AuthController::class, 'updateProfile']);
});
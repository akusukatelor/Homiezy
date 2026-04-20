<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnerController;


// --- GUEST ROUTES (Bisa diakses tanpa login) ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hasil-pencarian', [SearchController::class, 'index'])->name('search');
Route::get('/detail/{type}/{slug}', [SearchController::class, 'detail'])->name('service.detail');
Route::get('/jadi-mitra', [MitraController::class, 'index'])->name('mitra');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Socialite
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);


// --- AUTHENTICATED ROUTES (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    // User Dashboard & Profil
    Route::get('/dashboard-saya', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/lengkapi-profil', function() { return view('auth.complete-profile'); })->name('complete.profile');
    Route::post('/lengkapi-profil', [AuthController::class, 'updateProfile']);

    // FITUR CHECKOUT & BUNDLING (Pindahkan ke sini!)
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::post('/order/process/{id}', [OrderController::class, 'process'])->name('order.process');
    Route::post('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');

    // Fitur Mitra
    Route::get('/jadi-mitra/register', [PartnerController::class, 'create'])->name('partner.register');
    Route::post('/jadi-mitra/store', [PartnerController::class, 'store'])->name('partner.store');
    Route::get('/mitra/dashboard', [MitraController::class, 'dashboard'])->name('mitra.dashboard');

    // Wizard Paket
   Route::get('/paket/{type}', function ($type) {
    // Ambil data asli dari database
    $kosItems = Service::where('type', 'kos')->get();
    $cateringItems = Service::where('type', 'katering')->get();
    $laundryItems = Service::where('type', 'laundry')->get();

    $validTypes = ['premium', 'basic', 'standard-clean', 'standard-meal'];
    
    // Kirim data ke view
    if (in_array($type, $validTypes)) {
        return view('paket.' . $type, compact('kosItems', 'cateringItems', 'laundryItems'));
    }
    
    return view('paket-wizard', compact('type', 'kosItems', 'cateringItems', 'laundryItems'));
})->name('paket.wizard');
});
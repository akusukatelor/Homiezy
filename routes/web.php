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
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\XenditController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiProfileController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

//API Routes
Route::prefix('v1')->group(function () {

    // ── Public routes (tidak perlu login) ──────────────
    Route::post('/auth/login',    [ApiAuthController::class, 'login']);
    Route::post('/auth/register', [ApiAuthController::class, 'register']);
    Route::post('/auth/google',   [ApiAuthController::class, 'googleLogin']);

    // Layanan publik
    Route::get('/kos',                    [ApiServiceController::class, 'kos']);
    Route::get('/catering',               [ApiServiceController::class, 'catering']);
    Route::get('/laundry',                [ApiServiceController::class, 'laundry']);
    Route::get('/paket',                  [ApiServiceController::class, 'paket']);
    Route::get('/layanan/{type}/{slug}',  [ApiServiceController::class, 'detail']);

    // ── Protected routes (wajib login) ─────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
        Route::get('/auth/me',      [ApiAuthController::class, 'me']);

        // Orders
        Route::get('/orders',              [ApiOrderController::class, 'index']);
        Route::post('/orders',             [ApiOrderController::class, 'store']);
        Route::get('/orders/{id}',         [ApiOrderController::class, 'show']);
        Route::patch('/orders/{id}/cancel',[ApiOrderController::class, 'cancel']);

        // Profile
        Route::get('/profile',  [ApiProfileController::class, 'show']);
        Route::put('/profile',  [ApiProfileController::class, 'update']);
    });
});

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

// --- SUPER ADMIN ROUTES ---
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

    // Manajemen layanan
    Route::get('/layanan', [SuperAdminController::class, 'layanan'])->name('layanan');
    Route::delete('/layanan/{id}', [SuperAdminController::class, 'destroyLayanan'])->name('layanan.destroy');
    Route::patch('/layanan/{id}/toggle', [SuperAdminController::class, 'toggleLayanan'])->name('layanan.toggle');

    // Manajemen user
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
});

Route::post('/xendit/webhook', [XenditController::class, 'webhook'])
    ->name('xendit.webhook')
    ->withoutMiddleware([VerifyCsrfToken::class]);


// --- AUTHENTICATED ROUTES (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/create/{orderId}', [XenditController::class, 'createInvoice'])
        ->name('xendit.create');
    Route::get('/payment/success', [XenditController::class, 'success'])
        ->name('xendit.success');
    Route::get('/payment/failure', [XenditController::class, 'failure'])
        ->name('xendit.failure');

    // User Dashboard & Profil
    Route::get('/dashboard-saya', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/lengkapi-profil', function() { return view('auth.complete-profile'); })->name('complete.profile');
    Route::post('/lengkapi-profil', [AuthController::class, 'updateProfile']);

    Route::middleware(['role:mitra'])->prefix('mitra')->name('mitra.')->group(function () {
        Route::put('/update/{id}', [MitraController::class, 'update'])->name('update');
        Route::post('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');
    });

    // FITUR CHECKOUT & BUNDLING (Pindahkan ke sini!)
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::post('/order/process/{id}', [OrderController::class, 'process'])->name('order.process');
    Route::post('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');
    Route::patch('/order/cancel/{id}', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::get('/order/edit-item/{id}/{category}', [OrderController::class, 'editItem'])->name('order.edit_item');
    Route::put('/order/update-item/{id}', [OrderController::class, 'updateItem'])->name('order.update_item');

    // REVIEW
     Route::post('/review', [ReviewController::class, 'store'])->name('review.store');

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

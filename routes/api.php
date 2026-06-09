<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiProfileController;

Route::prefix('v1')->group(function () {

    // ── Public routes ──────────────────────────────────
    Route::post('/auth/login',    [ApiAuthController::class, 'login']);
    Route::post('/auth/register', [ApiAuthController::class, 'register']);
    Route::post('/auth/google',   [ApiAuthController::class, 'googleLogin']);

    // Layanan publik
    Route::get('/kos',                   [ApiServiceController::class, 'kos']);
    Route::get('/catering',              [ApiServiceController::class, 'catering']);
    Route::get('/laundry',               [ApiServiceController::class, 'laundry']);
    Route::get('/paket',                 [ApiServiceController::class, 'paket']);
    Route::get('/layanan/{type}/{slug}', [ApiServiceController::class, 'detail']);

    // ── Protected routes ───────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
        Route::get('/auth/me',      [ApiAuthController::class, 'me']);

        Route::get('/orders',               [ApiOrderController::class, 'index']);
        Route::post('/orders',              [ApiOrderController::class, 'store']);
        Route::get('/orders/{id}',          [ApiOrderController::class, 'show']);
        Route::patch('/orders/{id}/cancel', [ApiOrderController::class, 'cancel']);

        Route::get('/profile', [ApiProfileController::class, 'show']);
        Route::put('/profile', [ApiProfileController::class, 'update']);
    });
});

<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitraController;

Route::get('/hasil-pencarian', [SearchController::class, 'index'])->name('search');
Route::get('/detail/{type}/{slug}', [SearchController::class, 'detail'])->name('service.detail');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard-saya', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/jadi-mitra', [MitraController::class, 'index'])->name('mitra');

Route::get('/paket/{type}', function ($type) {
    if ($type === 'premium') {
        return view('paket.premium'); // Memanggil file khusus premium Anda
    }else if ($type === 'basic') {
        return view('paket.basic');
    }else if ($type === 'standard-clean') {
        return view('paket.standard-clean');
    }else if ($type === 'standard-meal') {
        return view('paket.standard-meal');
    }
    return view('paket-wizard', compact('type')); // Paket lain menggunakan template standar
})->name('paket.wizard');

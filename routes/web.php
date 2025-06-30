<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\InvoiceController;
use App\Http\Middleware\OwnerOnly;

// Redirect root ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// --------------------
// AUTHENTICATION ROUTES
// --------------------

// Guest routes (login)
Route::middleware('guest')->group(function() {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);   
    
    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management (Owner only)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('create');
    
    // Password Management
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change.update');
    
    // --------------------
    // KATEGORI ROUTES
    // --------------------
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::get('/tambah', [KategoriController::class, 'create'])->name('tambah');
        Route::post('/', [KategoriController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });
    
    // --------------------
    // BARANG ROUTES
    // --------------------
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::get('/tambah', [BarangController::class, 'create'])->name('tambah');
        Route::post('/tambah', [BarangController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
    });
    
    // --------------------
    // PESANAN ROUTES
    // --------------------
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [PesananController::class, 'index'])->name('index');
        Route::get('/tambah', [PesananController::class, 'create'])->name('tambah');
        Route::post('/tambah', [PesananController::class, 'store'])->name('store');
        Route::put('/{id}/selesai', [PesananController::class, 'selesai'])->name('selesai');
        Route::put('/status/{id}', [PesananController::class, 'updateStatus'])->name('updateStatus');
    });
    
    // --------------------
    // INVOICE ROUTES
    // --------------------
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{id}/cetak', [InvoiceController::class, 'cetak'])->name('cetak');
    });
    
    // --------------------
    // REPORT ROUTES
    // --------------------
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [PesananController::class, 'report'])->name('index');
        Route::get('/penyewaan/pdf', [PesananController::class, 'reportPdf'])->name('penyewaan.pdf');
    });
});
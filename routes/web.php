<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Middleware\OwnerOnly;


// Redirect root ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});


// --------------------
// AUTH ROUTES
// --------------------

//LOGIN
// Login (hanya untuk guest)
Route::middleware('guest')->group(function() {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);   
});

// Logout (hanya untuk user yang sudah login)
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');


// --------------------
// PROTECTED ROUTES
// --------------------
Route::middleware(['auth'])->group(function () {
    //DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CREATE USER
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('create');
     
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::get('/tambah', [KategoriController::class, 'create'])->name('tambah');
        Route::post('/', [KategoriController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    //BARANG
    //Input Barang
    Route::get('/barang/tambah', [BarangController::class, 'create'])->name('barang.tambah');
    Route::post('/barang/tambah', [BarangController::class, 'store'])->name('barang.store');
    //List Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    // Edit Barang
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    // Hapus Barang
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    //PESANAN
    //Tambah Pesanan
    Route::get('/pesanan/tambah', [PesananController::class, 'create'])->name('pesanan.tambah');
    Route::post('/pesanan/tambah', [PesananController::class, 'store'])->name('pesanan.store');
    //List Pesanan
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    //Selesai Pesanan
    //Route::put('/pesanan/{id}/selesai', [PesananController::class, 'selesaikan'])->name('pesanan.selesai');
    Route::put('/pesanan/{id}/selesai', [PesananController::class, 'selesai'])->name('pesanan.selesai');
    //Batal Pesanan
    Route::put('/pesanan/status/{id}', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

    //INVOICE
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/cetak', [InvoiceController::class, 'cetak'])->name('invoice.cetak');

    // REPORT PENJUALAN
    Route::get('/report', [PesananController::class, 'report'])->name('report.index');
    Route::get('/report-penyewaan/pdf', [PesananController::class, 'reportPdf'])->name('report.penyewaan.pdf');

    //CHANGE PASSWORD
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change.update');

});
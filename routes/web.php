<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RblAnalysisController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 1. Root route - langsung dialihkan ke login
Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

// 2. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Authenticated Application Routes (Protected with Shared Unified Layout)
Route::middleware(['auth'])->group(function () {
    // Dashboard (Role-Aware)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications/feed', [DashboardController::class, 'notificationsFeed'])->name('notifications.feed');

    // Data Master Produk Tekstil
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Transaksi Persediaan (Stok Masuk & Keluar)
    Route::get('/transaksi/masuk', [TransactionController::class, 'indexMasuk'])->name('transaksi.masuk');
    Route::post('/transaksi/masuk', [TransactionController::class, 'storeMasuk'])->name('transaksi.masuk.store');
    Route::get('/transaksi/keluar', [TransactionController::class, 'indexKeluar'])->name('transaksi.keluar');
    Route::post('/transaksi/keluar', [TransactionController::class, 'storeKeluar'])->name('transaksi.keluar.store');

    // Modul Finansial & Nota Pembelian (Purchasing & Procurement)
    Route::get('/purchasing/nota', [TransactionController::class, 'indexNota'])->name('purchasing.nota.index');
    Route::get('/purchasing/nota/{id}/cetak', [TransactionController::class, 'cetakNota'])->name('purchasing.nota.cetak');
    Route::put('/purchasing/nota/{id}/status', [TransactionController::class, 'updateStatusBayar'])->name('purchasing.nota.updateStatus');

    // Analisis Buffer RBL & Rekomendasi Reorder
    Route::get('/rbl/analisis', [RblAnalysisController::class, 'index'])->name('rbl.analisis');

    // Laporan & Panduan
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/panduan', [ReportController::class, 'panduan'])->name('panduan.index');

    // Pengaturan Profil & Keamanan Akun
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Manajemen Pengguna (Superadmin Only)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

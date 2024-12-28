<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\GlobalSearchController;

// Public routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Routes untuk staff dan admin
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::resource('barangMasuk', BarangMasukController::class);
    Route::resource('barangKeluar', BarangKeluarController::class);
    Route::resource('Peminjaman', PeminjamanController::class);
    Route::get('/search-barang', [HomeController::class, 'searchBarang'])->name('search.barang');
    
    // Routes khusus admin
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/home', [HomeController::class, 'store'])->name('home.store');
        Route::put('/home/{id}', [HomeController::class, 'update'])->name('home.update');
        Route::delete('/home/{id}', [HomeController::class, 'destroy'])->name('home.destroy');
        Route::get('/stock', [ExportController::class, 'index'])->name('stock.index');
    });
});

Route::resource('home', HomeController::class);
Route::resource('barangMasuk', BarangMasukController::class);
Route::resource('barangKeluar', BarangKeluarController::class);
Route::resource('stock', ExportController::class);
Route::resource('Peminjaman', PeminjamanController::class);
Route::put('/peminjaman/{id}/return', [PeminjamanController::class, 'returnItem'])->name('peminjaman.return');
Route::resource('peminjaman', PeminjamanController::class);
Route::get('/search-barang', [HomeController::class, 'searchBarang'])->name('search.barang');
Route::get('/search', [GlobalSearchController::class, 'search'])->name('global.search');

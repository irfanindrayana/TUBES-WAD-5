<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\userloginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\Auth\RegisterController;

// Public routes
Route::get('/', [userloginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [userloginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [userloginController::class, 'logout'])->name('logout');
    
    // Dashboard route
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Routes untuk staff dan admin
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::resource('home', HomeController::class);
    Route::get('/export', [HomeController::class, 'export'])->name('home.export');
    Route::resource('barangMasuk', BarangMasukController::class);
    Route::resource('barangKeluar', BarangKeluarController::class);
    
    Route::resource('Peminjaman', PeminjamanController::class)->names([
        'index' => 'peminjaman.index',
        'create' => 'peminjaman.create',
        'store' => 'peminjaman.store',
        'show' => 'peminjaman.show',
        'edit' => 'peminjaman.edit',
        'update' => 'peminjaman.update',
        'destroy' => 'peminjaman.destroy',
    ]);
    Route::put('/Peminjaman/{id}/return', [PeminjamanController::class, 'returnItem'])->name('peminjaman.return');
    
    Route::get('/search-barang', [HomeController::class, 'searchBarang'])->name('search.barang');
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('global.search');
    Route::get('/home/{id}/detail', [HomeController::class, 'detail'])->name('home.detail');
    Route::put('/home/{id}/stok-minimal', [HomeController::class, 'updateStokMinimal'])->name('home.updateStokMinimal');
});

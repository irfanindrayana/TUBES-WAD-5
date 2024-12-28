<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangMasukController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('home', HomeController::class);
Route::resource('barangMasuk', BarangMasukController::class);
// Route::get('/barangMasuk', [BarangMasukController::class, 'index'])->name('barang_masuk.index'); 

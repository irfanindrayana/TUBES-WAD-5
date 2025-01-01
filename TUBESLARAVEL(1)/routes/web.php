<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\ExportController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('home', HomeController::class);
Route::resource('barangMasuk', BarangMasukController::class);
Route::resource('barangKeluar', BarangKeluarController::class);
Route::resource('stock', ExportController::class);
// Route::get('/stock', [HomeController::class, 'index'])->name('stock.index');
// Route::get('/barangMasuk', [BarangMasukController::class, 'index'])->name('barang_masuk.index'); 

Route::get('/barang-keluar/surat-jalan-manual', [BarangKeluarController::class, 'barangKeluarManual'])->name('barangKeluar.surat.manual');
Route::get('/barang-keluar/surat-jalan/{id}', [BarangKeluarController::class, 'barangKeluar'])->name('barangKeluar.surat'); 

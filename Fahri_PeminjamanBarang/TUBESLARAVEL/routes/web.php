<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('home', HomeController::class);
Route::resource('Peminjaman', PeminjamanController::class);
Route::put('/peminjaman/{id}/return', [PeminjamanController::class, 'returnItem'])->name('peminjaman.return');
Route::resource('peminjaman', PeminjamanController::class);


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;

class ExportController extends Controller
{
    public function index()
    {
        $homes = Home::all(); // Ambil semua data dari tabel Barang
        return view('stock.index', compact('homes')); // Kirim data ke view
    }
}

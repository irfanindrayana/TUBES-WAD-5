<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        // Cari di tabel Home (Gudang)
        $barang = Home::where('namaBarang', 'LIKE', "%{$query}%")
                     ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                     ->get();

        // Cari di tabel Barang Masuk
        $barangMasuk = BarangMasuk::where('nama_barang', 'LIKE', "%{$query}%")
                                 ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                                 ->get();

        // Cari di tabel Barang Keluar
        $barangKeluar = BarangKeluar::where('nama_barang', 'LIKE', "%{$query}%")
                                   ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                                   ->get();

        // Cari di tabel Peminjaman
        $peminjaman = Peminjaman::where('nama_peminjam', 'LIKE', "%{$query}%")
                               ->orWhere('nama_barang', 'LIKE', "%{$query}%")
                               ->get();

        return view('search.results', compact(
            'query',
            'barang',
            'barangMasuk',
            'barangKeluar',
            'peminjaman'
        ));
    }
} 
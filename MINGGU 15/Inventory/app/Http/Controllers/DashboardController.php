<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data untuk Home/Stock
        $totalHome = Home::count();
        $lastHomeUpdate = Home::latest()->first()?->updated_at?->format('d M Y H:i');

        // Mengambil data Barang Masuk
        $totalBarangMasuk = BarangMasuk::count();
        $lastBarangMasuk = BarangMasuk::latest()->first()?->created_at?->format('d M Y H:i');

        // Mengambil data Barang Keluar
        $totalBarangKeluar = BarangKeluar::count();
        $lastBarangKeluar = BarangKeluar::latest()->first()?->created_at?->format('d M Y H:i');

        // Mengambil data Peminjaman
        $totalPeminjaman = Peminjaman::where('status', 'dipinjam')->count();
        $lastPeminjaman = Peminjaman::latest()->first()?->created_at?->format('d M Y H:i');

        // Mengambil aktivitas terakhir (10 terakhir)
        $recentActivities = collect();
        
        // Menambahkan aktivitas dari berbagai model
        $recentActivities = $recentActivities->merge(
            Home::latest()->take(3)->get()->map(function($item) {
                return "Stock: {$item->nama_barang} diperbarui pada " . $item->updated_at->format('d M Y H:i');
            })
        )->merge(
            BarangMasuk::latest()->take(3)->get()->map(function($item) {
                return "Barang Masuk: {$item->nama_barang} ({$item->jumlah}) pada " . $item->created_at->format('d M Y H:i');
            })
        )->merge(
            BarangKeluar::latest()->take(3)->get()->map(function($item) {
                return "Barang Keluar: {$item->nama_barang} ({$item->jumlah}) pada " . $item->created_at->format('d M Y H:i');
            })
        )->merge(
            Peminjaman::latest()->take(3)->get()->map(function($item) {
                return "Peminjaman: {$item->nama_barang} ({$item->jumlah}) - {$item->status} pada " . $item->created_at->format('d M Y H:i');
            })
        )->sortByDesc(function($item) {
            return strtotime(substr($item, -16));
        })->take(10);

        return view('dashboard.index', compact(
            'totalHome',
            'lastHomeUpdate',
            'totalBarangMasuk',
            'lastBarangMasuk',
            'totalBarangKeluar',
            'lastBarangKeluar',
            'totalPeminjaman',
            'lastPeminjaman',
            'recentActivities'
        ));
    }
} 
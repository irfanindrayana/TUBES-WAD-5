<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $datastok = Home::whereRaw('stok <= stok_minimal')->get();
        $homes = Home::all();
        
        return view('home.index', compact('datastok', 'homes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaBarang' => 'required',
            'deskripsi' => 'required',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'namaBarang' => $request->namaBarang,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok
        ];

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . '.' . $gambar->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('gambar', $gambar, $nama_gambar);
            $data['gambar'] = $nama_gambar;
        }

        Home::create($data);

        return redirect()->route('home.index')
            ->with('success', 'Barang "' . $request->namaBarang . '" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaBarang' => 'required',
            'deskripsi' => 'required',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $home = Home::findOrFail($id);
        $oldNamaBarang = $home->namaBarang; // Simpan nama barang lama

        // Update data dasar
        $data = [
            'namaBarang' => $request->namaBarang,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok
        ];

        // Handle upload gambar jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($home->gambar && Storage::disk('public')->exists('gambar/' . $home->gambar)) {
                Storage::disk('public')->delete('gambar/' . $home->gambar);
            }

            $gambar = $request->file('gambar');
            $nama_gambar = time() . '.' . $gambar->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('gambar', $gambar, $nama_gambar);
            $data['gambar'] = $nama_gambar;
        }

        $home->update($data);

        return redirect()->route('home.index')
            ->with('success', 'Barang "' . $oldNamaBarang . '" berhasil diupdate menjadi "' . $request->namaBarang . '"!');
    }

    public function destroy($id)
    {
        $home = Home::findOrFail($id);
        $namaBarang = $home->namaBarang; // Simpan nama barang sebelum dihapus

        // Hapus gambar jika ada
        if ($home->gambar && Storage::disk('public')->exists('gambar/' . $home->gambar)) {
            Storage::disk('public')->delete('gambar/' . $home->gambar);
        }

        $home->delete();

        return redirect()->route('home.index')
            ->with('success', 'Barang "' . $namaBarang . '" berhasil dihapus!');
    }

    public function searchBarang(Request $request)
    {
        $keyword = $request->get('keyword');
        $barang = Home::where('namaBarang', 'LIKE', "%{$keyword}%")->get();
        
        return response()->json($barang);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $barang = Home::where('namaBarang', 'LIKE', "%{$query}%")
            ->select('id', 'namaBarang', 'gambar', 'stok')
            ->get();
        
        return response()->json($barang);
    }

    public function export()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home.index')
                           ->with('error', 'Unauthorized action. This page is only accessible by admin.');
        }

        $homes = Home::all();
        return view('stock.index', compact('homes'));
    }

    public function detail($id)
    {
        $barang = Home::findOrFail($id);
        
        // Mengambil histori barang masuk
        $barangMasuk = \App\Models\BarangMasuk::where('nama_barang', $barang->namaBarang)
            ->orderBy('tanggal', 'desc')
            ->get();
            
        // Mengambil histori barang keluar
        $barangKeluar = \App\Models\BarangKeluar::where('nama_barang', $barang->namaBarang)
            ->orderBy('tanggal', 'desc')
            ->get();
            
        // Mengambil histori peminjaman
        $peminjaman = \App\Models\Peminjaman::where('nama_barang', $barang->namaBarang)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('home.detail', compact('barang', 'barangMasuk', 'barangKeluar', 'peminjaman'));
    }

    public function updateStokMinimal(Request $request, $id)
    {
        // Validasi hanya admin yang bisa mengakses
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home.detail', $id)
                ->with('error', 'Hanya admin yang dapat mengubah stok minimal.');
        }

        $request->validate([
            'stok_minimal' => 'required|integer|min:1'
        ]);

        $barang = Home::findOrFail($id);
        $barang->stok_minimal = $request->stok_minimal;
        $barang->save();

        return redirect()->route('home.detail', $id)
            ->with('success', 'Stok minimal untuk "' . $barang->namaBarang . '" berhasil diupdate!');
    }
}

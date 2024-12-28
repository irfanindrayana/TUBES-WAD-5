<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;
use App\Models\Peminjaman;

class HomeController extends Controller
{
    public function index()
    {
        $datastok = Home::where('stok', '<', 5)->get();
        $homes = Home::all();
        
        return view('home.index', compact('datastok', 'homes'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'namaBarang' => 'required|string|unique:home,namaBarang',
                'deskripsi' => 'required|string',
                'stok' => 'required|integer|min:0',
            ]);

            Home::create($validatedData);
            return redirect()->route('home.index')->with('success', 'Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'namaBarang' => 'required|string|unique:home,namaBarang,' . $id,
                'deskripsi' => 'required|string',
                'stok' => 'required|integer|min:0',
            ]);

            $home = Home::findOrFail($id);
            $home->update($validatedData);

            return redirect()->route('home.index')->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $home = Home::findOrFail($id);
            
            $peminjaman = Peminjaman::where('nama_barang', $home->namaBarang)
                                  ->where('status', 'dipinjam')
                                  ->first();
            
            if ($peminjaman) {
                throw new \Exception('Barang sedang dipinjam dan tidak dapat dihapus');
            }

            $home->delete();
            return redirect()->route('home.index')->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}

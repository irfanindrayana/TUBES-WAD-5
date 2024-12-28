<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('user')->get();
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $barang = Home::where('stok', '>', 0)->get();
        return view('peminjaman.create', compact('barang'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_peminjam' => 'required|string',
                'nama_barang' => 'required|exists:home,namaBarang',
                'tanggal_pinjam' => 'required|date|after_or_equal:today',
                'tanggal_kembali' => 'nullable|date|after:tanggal_pinjam'
            ]);

            $barang = Home::where('namaBarang', $request->nama_barang)->first();
            
            if (!$barang || $barang->stok <= 0) {
                throw new \Exception('Stok barang tidak tersedia');
            }

            \DB::beginTransaction();
            try {
                // Kurangi stok
                $barang->stok -= 1;
                $barang->save();

                // Buat peminjaman
                $peminjaman = Peminjaman::create([
                    'user_id' => 1,
                    'nama_peminjam' => $request->nama_peminjam,
                    'nama_barang' => $request->nama_barang,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $request->tanggal_kembali,
                    'status' => 'dipinjam'
                ]);

                \DB::commit();
                return redirect()->route('peminjaman.index')
                    ->with('success', 'Peminjaman berhasil ditambahkan');
            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        try {
            $request->validate([
                'nama_peminjam' => 'required|string',
                'nama_barang' => 'required|exists:home,namaBarang',
                'tanggal_pinjam' => 'required|date',
                'tanggal_kembali' => 'nullable|date|after:tanggal_pinjam',
                'status' => 'required|in:dipinjam,dikembalikan'
            ]);

            \DB::beginTransaction();
            try {
                if ($request->status == 'dikembalikan' && $peminjaman->status == 'dipinjam') {
                    $barang = Home::where('namaBarang', $peminjaman->nama_barang)->first();
                    if ($barang) {
                        $barang->stok += 1;
                        $barang->save();
                    }
                }

                $peminjaman->update($request->all());
                \DB::commit();

                return redirect()->route('peminjaman.index')
                    ->with('success', 'Peminjaman berhasil diperbarui');
            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Peminjaman $peminjaman)
    {
        try {
            $peminjaman->delete();
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}

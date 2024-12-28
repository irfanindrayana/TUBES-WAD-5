<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokHampirHabis = DB::select('SELECT * FROM barang_keluars WHERE jumlah < ?', [5]);
        $barangKeluar = DB::select('SELECT * FROM barang_keluars');
        return view('barang_keluar.index', compact('stokHampirHabis', 'barangKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'tanggal' => 'required|date',
                'nama_barang' => 'required|string',
                'gambar' => 'nullable',
                'deskripsi' => 'required|string',
                'jumlah' => 'required|integer|min:1',
                'admin' => 'required|string'
            ]);

            // Cek ketersediaan barang di home
            $home = Home::where('namaBarang', $validatedData['nama_barang'])->first();
            
            if (!$home) {
                return redirect()->back()
                    ->with('error', 'Barang tidak ditemukan dalam stok!')
                    ->withInput();
            }

            // Cek apakah stok mencukupi
            if ($home->stok < $validatedData['jumlah']) {
                return redirect()->back()
                    ->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $home->stok)
                    ->withInput();
            }

            // Gunakan gambar dari data home jika tidak ada upload baru
            if (!$request->hasFile('gambar') && isset($request->gambar)) {
                $validatedData['gambar'] = $request->gambar;
            }
            // Jika ada file gambar baru
            elseif ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/gambar', $namaFile);
                $validatedData['gambar'] = $namaFile;
            }

            // Simpan data barang keluar
            BarangKeluar::create($validatedData);

            // Kurangi stok di home
            $home->update([
                'stok' => $home->stok - $validatedData['jumlah']
            ]);

            return redirect()->route('barangKeluar.index')
                ->with('success', 'Barang keluar berhasil dicatat dan stok berkurang');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $barangKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $barangKeluar = BarangKeluar::findOrFail($id);
            $oldJumlah = $barangKeluar->jumlah;

            $validatedData = $request->validate([
                'nama_barang' => 'required|string',
                'deskripsi' => 'required|string',
                'jumlah' => 'required|integer|min:1',
            ]);

            $home = Home::where('namaBarang', $validatedData['nama_barang'])->first();
            
            if (!$home) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan dalam stok!');
            }

            // Hitung selisih jumlah
            $selisih = $validatedData['jumlah'] - $oldJumlah;
            
            // Cek apakah stok mencukupi jika ada penambahan jumlah
            if ($selisih > 0 && $home->stok < $selisih) {
                return redirect()->back()
                    ->with('error', 'Stok tidak mencukupi untuk penambahan! Stok tersedia: ' . $home->stok);
            }

            // Update stok di home
            $home->update([
                'stok' => $home->stok - $selisih
            ]);

            // Update data barang keluar
            $barangKeluar->update($validatedData);

            return redirect()->route('barangKeluar.index')
                ->with('success', 'Data barang keluar berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $barangKeluar = BarangKeluar::findOrFail($id);
            
            // Kembalikan stok ke home
            $home = Home::where('namaBarang', $barangKeluar->nama_barang)->first();
            if ($home) {
                $home->update([
                    'stok' => $home->stok + $barangKeluar->jumlah
                ]);
            }

            $barangKeluar->delete();

            return redirect()->route('barangKeluar.index')
                ->with('success', 'Data barang keluar berhasil dihapus dan stok dikembalikan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

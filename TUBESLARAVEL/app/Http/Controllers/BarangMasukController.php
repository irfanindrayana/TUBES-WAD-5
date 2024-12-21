<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Http\Requests\StoreBarangMasukRequest;
use App\Http\Requests\UpdateBarangMasukRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Home;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $barangMasuk = BarangMasuk::all();
        // $stokHampirHabis = BarangMasuk::where('jumlah', '<=', 10)->get();
    
        // return view('barang_masuk.index', compact('barangMasuk', 'stokHampirHabis'));

        $stokHampirHabis = DB::select('SELECT * FROM barang_masuks WHERE jumlah < ?', [5]);
        $barangMasuk = DB::select('SELECT * FROM barang_masuks');
        return view('barang_masuk.index', compact('barangMasuk', 'stokHampirHabis'));
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
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'deskripsi' => 'required|string',
                'jumlah' => 'required|integer|min:1',
                'admin' => 'required|string'
            ]);

            // Upload gambar jika ada
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                // Simpan gambar ke folder public/storage/gambar
                $file->storeAs('public/gambar', $namaFile);
                $validatedData['gambar'] = $namaFile;
            }

            // Jika tidak upload gambar baru, gunakan gambar yang sudah ada
            if (!$request->hasFile('gambar') && $request->has('existing_gambar')) {
                $validatedData['gambar'] = $request->existing_gambar;
            }

            // Simpan ke tabel barang_masuks
            BarangMasuk::create($validatedData);

            // Update atau buat data di tabel home
            $home = Home::where('namaBarang', $validatedData['nama_barang'])->first();

            if ($home) {
                // Update stok jika barang sudah ada
                $home->update([
                    'stok' => $home->stok + $validatedData['jumlah']
                ]);
            } else {
                // Buat data baru jika barang belum ada
                Home::create([
                    'namaBarang' => $validatedData['nama_barang'],
                    'deskripsi' => $validatedData['deskripsi'],
                    'stok' => $validatedData['jumlah'],
                    'gambar' => $validatedData['gambar'] ?? null
                ]);
            }

            return redirect()->route('barangMasuk.index')
                ->with('success', 'Barang masuk berhasil ditambahkan');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangMasuk $barangMasuk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarangMasukRequest $request, BarangMasuk $barangMasuk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        //
    }
}

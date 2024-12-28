<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangKeluar = DB::table('barang_keluars')
            ->join('users', 'barang_keluars.user_id', '=', 'users.id')
            ->select('barang_keluars.*', 'users.name as user_name')
            ->get();

        return view('barang_keluar.index', compact('barangKeluar'));
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
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required',
            'jumlah' => 'required|integer|min:1',
            'deskripsi' => 'required',
            'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_default' => 'nullable|string'
        ]);

        try {
            // Validasi stok
            $barang = Home::where('namaBarang', $request->nama_barang)->first();
            if (!$barang) {
                return redirect()->back()
                    ->with('error', 'Barang tidak ditemukan')
                    ->withInput();
            }

            if ($request->jumlah > $barang->stok) {
                return redirect()->back()
                    ->with('error', 'Jumlah melebihi stok yang tersedia')
                    ->withInput();
            }

            // Handle gambar
            if ($request->hasFile('gambar_baru')) {
                $file = $request->file('gambar_baru');
                $extension = $file->getClientOriginalExtension();
                $fileName = str_replace(' ', '_', $request->nama_barang) . '_' . time() . '.' . $extension;
                
                // Pindahkan file ke storage
                if ($file->move(public_path('storage/gambar'), $fileName)) {
                    $gambar = $fileName;
                    \Log::info('Gambar berhasil diupload: ' . $fileName);
                } else {
                    \Log::error('Gagal mengupload gambar');
                    $gambar = $barang ? $barang->gambar : 'no-image.png';
                }
            } else {
                // Gunakan gambar dari barang yang dipilih
                $gambar = $barang ? $barang->gambar : 'no-image.png';
            }

            $data = [
                'tanggal' => $request->tanggal,
                'nama_barang' => $request->nama_barang,
                'jumlah' => $request->jumlah,
                'deskripsi' => $request->deskripsi,
                'gambar' => $gambar,
                'admin' => Auth::user()->name,
                'user_id' => auth()->id()
            ];

            BarangKeluar::create($data);

            // Update stok
            $barang->update([
                'stok' => $barang->stok - $request->jumlah
            ]);

            return redirect()->route('barangKeluar.index')
                ->with('success', 'Data barang keluar berhasil ditambahkan');

        } catch (\Exception $e) {
            \Log::error('Error in barang keluar store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage())
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

            $validated = $request->validate([
                'tanggal' => 'required|date',
                'nama_barang' => 'required|string',
                'deskripsi' => 'required|string',
                'jumlah' => 'required|integer|min:1',
                'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar_default' => 'nullable|string'
            ]);

            // Validasi stok jika ada perubahan jumlah
            $barang = Home::where('namaBarang', $request->nama_barang)->first();
            if (!$barang) {
                return redirect()->back()
                    ->with('error', 'Barang tidak ditemukan')
                    ->withInput();
            }

            // Hitung selisih jumlah
            $selisih = $request->jumlah - $oldJumlah;
            
            // Cek apakah stok mencukupi jika ada penambahan jumlah
            if ($selisih > 0 && $barang->stok < $selisih) {
                return redirect()->back()
                    ->with('error', 'Stok tidak mencukupi untuk penambahan! Stok tersedia: ' . $barang->stok)
                    ->withInput();
            }

            // Handle gambar
            if ($request->hasFile('gambar_baru')) {
                // Hapus gambar lama jika ada dan bukan gambar default
                if ($barangKeluar->gambar && $barangKeluar->gambar != 'no-image.png' && 
                    file_exists(public_path('storage/gambar/' . $barangKeluar->gambar))) {
                    unlink(public_path('storage/gambar/' . $barangKeluar->gambar));
                }

                $file = $request->file('gambar_baru');
                $extension = $file->getClientOriginalExtension();
                $fileName = str_replace(' ', '_', $request->nama_barang) . '_' . time() . '.' . $extension;
                
                // Pindahkan file ke storage
                if ($file->move(public_path('storage/gambar'), $fileName)) {
                    $gambar = $fileName;
                    \Log::info('Gambar berhasil diupload: ' . $fileName);
                } else {
                    \Log::error('Gagal mengupload gambar');
                    $gambar = $request->gambar_default;
                }
            } else {
                // Gunakan gambar dari barang yang dipilih jika berbeda barang
                if ($barangKeluar->nama_barang != $request->nama_barang) {
                    $gambar = $barang ? $barang->gambar : 'no-image.png';
                } else {
                    $gambar = $request->gambar_default;
                }
            }

            // Update data barang keluar
            $barangKeluar->update([
                'tanggal' => $request->tanggal,
                'nama_barang' => $request->nama_barang,
                'jumlah' => $request->jumlah,
                'deskripsi' => $request->deskripsi,
                'gambar' => $gambar
            ]);

            // Update stok di home
            $barang->update([
                'stok' => $barang->stok - $selisih
            ]);

            return redirect()->route('barangKeluar.index')
                ->with('success', 'Data barang keluar berhasil diperbarui');

        } catch (\Exception $e) {
            \Log::error('Error in barang keluar update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
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

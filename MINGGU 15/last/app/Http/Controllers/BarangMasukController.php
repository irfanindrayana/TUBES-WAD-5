<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuk = DB::table('barang_masuks')
            ->leftJoin('users', 'barang_masuks.user_id', '=', 'users.id')
            ->select('barang_masuks.*', 'users.name as user_name')
            ->orderBy('tanggal', 'desc')
            ->get();

        $stokHampirHabis = Home::where('stok', '<', 5)->get();
        $barang = Home::all();

        return view('barang_masuk.index', compact('barangMasuk', 'stokHampirHabis', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required',
            'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_default' => 'nullable|string',
            'deskripsi' => 'required',
            'jumlah' => 'required|integer|min:1',
            'admin' => 'required'
        ]);

        try {
            // Konversi tanggal ke format yang benar
            $tanggal = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d H:i:s');
            
            // Cek apakah barang sudah ada di tabel homes
            $existingBarang = Home::where('namaBarang', $request->nama_barang)->first();

            // Handle gambar
            if ($request->hasFile('gambar_baru')) {
                $file = $request->file('gambar_baru');
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('storage/gambar'), $fileName);
                $gambar = $fileName;
            } else {
                // Jika barang sudah ada, gunakan gambar yang ada
                if ($existingBarang) {
                    $gambar = $existingBarang->gambar;
                } else {
                    // Jika barang baru dan tidak ada upload gambar, gunakan gambar default
                    $gambar = 'no-image.png';
                }
            }

            // Simpan data barang masuk
            BarangMasuk::create([
                'tanggal' => $tanggal,
                'nama_barang' => $request->nama_barang,
                'gambar' => $gambar,
                'deskripsi' => $request->deskripsi,
                'jumlah' => $request->jumlah,
                'admin' => Auth::user()->name,
                'user_id' => auth()->id()
            ]);

            // Update atau buat data di tabel homes
            if ($existingBarang) {
                // Update stok jika barang sudah ada
                $existingBarang->update([
                    'stok' => $existingBarang->stok + $request->jumlah
                ]);
            } else {
                // Buat data baru jika barang belum ada
                Home::create([
                    'namaBarang' => $request->nama_barang,
                    'deskripsi' => $request->deskripsi,
                    'stok' => $request->jumlah,
                    'gambar' => $gambar
                ]);
            }

            return redirect()->route('barangMasuk.index')
                ->with('success', 'Data barang masuk berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string',
            'deskripsi' => 'required|string',
            'jumlah' => 'required|integer|min:1'
        ]);

        $barangMasuk = BarangMasuk::findOrFail($id);
        
        // Konversi tanggal ke format yang benar
        $data = $request->all();
        $data['tanggal'] = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d H:i:s');
        
        $barangMasuk->update($data);

        return redirect()->route('barangMasuk.index')
            ->with('success', 'Barang masuk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->delete();

        return redirect()->route('barangMasuk.index')
            ->with('success', 'Barang masuk berhasil dihapus!');
    }
}

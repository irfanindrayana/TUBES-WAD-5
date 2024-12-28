<?php

namespace App\Http\Controllers;
use App\Models\Peminjaman;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        // Pastikan direktori gambar ada
        if (!Storage::exists('public/gambar')) {
            Storage::makeDirectory('public/gambar');
        }

        // Cek dan salin no-image.png jika belum ada
        $noImagePath = storage_path('app/public/gambar/no-image.png');
        if (!file_exists($noImagePath)) {
            // Coba cari no-image.png dari beberapa lokasi
            $possibleLocations = [
                public_path('images/no-image.png'),
                resource_path('images/no-image.png'),
                base_path('no-image.png')
            ];

            foreach ($possibleLocations as $location) {
                if (file_exists($location)) {
                    copy($location, $noImagePath);
                    break;
                }
            }

            // Jika masih tidak ada, buat gambar default sederhana
            if (!file_exists($noImagePath)) {
                $this->createDefaultNoImage($noImagePath);
            }
        }
    }

    private function createDefaultNoImage($path)
    {
        // Buat gambar sederhana 100x100 pixel
        $image = imagecreatetruecolor(100, 100);
        $bgColor = imagecolorallocate($image, 200, 200, 200);
        $textColor = imagecolorallocate($image, 100, 100, 100);
        
        // Isi background
        imagefilledrectangle($image, 0, 0, 100, 100, $bgColor);
        
        // Tambah text "No Image"
        imagestring($image, 2, 20, 40, "No Image", $textColor);
        
        // Simpan gambar
        imagepng($image, $path);
        imagedestroy($image);
    }

    public function index()
    {
        $peminjaman = Peminjaman::with('user')->get();
        $barang = Home::all();
        return view('peminjaman.index', compact('peminjaman', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required',
            'nama_barang' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_default' => 'nullable|string',
            'jumlah_barang' => 'required|integer|min:1'
        ]);

        // Ambil data barang
        $barang = Home::where('namaBarang', $request->nama_barang)->first();
        
        // Validasi jumlah barang
        if ($barang && $request->jumlah_barang > $barang->stok) {
            return redirect()->back()
                ->with('error', 'Jumlah barang melebihi stok yang tersedia')
                ->withInput();
        }

        try {
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
                'user_id' => auth()->id(),
                'nama_peminjam' => $request->nama_peminjam,
                'nama_barang' => $request->nama_barang,
                'gambar' => $gambar,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'dipinjam',
                'admin' => Auth::user()->name,
                'jumlah_barang' => $request->jumlah_barang
            ];

            Peminjaman::create($data);

            // Kurangi stok barang
            if ($barang) {
                $barang->update([
                    'stok' => $barang->stok - $request->jumlah_barang
                ]);
            }

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil ditambahkan');

        } catch (\Exception $e) {
            \Log::error('Error in peminjaman store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan peminjaman: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required',
            'nama_barang' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_default' => 'nullable|string',
            'jumlah_barang' => 'required|integer|min:1'
        ]);

        try {
            // Handle gambar
            if ($request->hasFile('gambar_baru')) {
                // Hapus gambar lama jika ada dan bukan gambar default
                if ($peminjaman->gambar && $peminjaman->gambar != 'no-image.png' && 
                    file_exists(public_path('storage/gambar/' . $peminjaman->gambar))) {
                    unlink(public_path('storage/gambar/' . $peminjaman->gambar));
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
                if ($peminjaman->nama_barang != $request->nama_barang) {
                    $barang = Home::where('namaBarang', $request->nama_barang)->first();
                    $gambar = $barang ? $barang->gambar : 'no-image.png';
                } else {
                    $gambar = $request->gambar_default;
                }
            }

            // Update stok barang jika nama barang atau jumlah berubah
            if ($peminjaman->nama_barang != $request->nama_barang || 
                $peminjaman->jumlah_barang != $request->jumlah_barang) {
                
                // Kembalikan stok barang lama
                $barangLama = Home::where('namaBarang', $peminjaman->nama_barang)->first();
                if ($barangLama) {
                    $barangLama->update([
                        'stok' => $barangLama->stok + $peminjaman->jumlah_barang
                    ]);
                }

                // Kurangi stok barang baru
                $barangBaru = Home::where('namaBarang', $request->nama_barang)->first();
                if ($barangBaru) {
                    if ($request->jumlah_barang > $barangBaru->stok) {
                        return redirect()->back()
                            ->with('error', 'Jumlah barang melebihi stok yang tersedia')
                            ->withInput();
                    }

                    $barangBaru->update([
                        'stok' => $barangBaru->stok - $request->jumlah_barang
                    ]);
                }
            }

            $peminjaman->update([
                'nama_peminjam' => $request->nama_peminjam,
                'nama_barang' => $request->nama_barang,
                'gambar' => $gambar,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jumlah_barang' => $request->jumlah_barang
            ]);

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil diperbarui');

        } catch (\Exception $e) {
            \Log::error('Error in peminjaman update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui peminjaman: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Peminjaman $peminjaman)
    {
        try {
            // Hapus gambar jika ada dan bukan gambar default
            if ($peminjaman->gambar && $peminjaman->gambar != 'no-image.png' && 
                Storage::exists('public/gambar/' . $peminjaman->gambar)) {
                Storage::delete('public/gambar/' . $peminjaman->gambar);
            }

            // Kembalikan stok barang
            $barang = Home::where('namaBarang', $peminjaman->nama_barang)->first();
            if ($barang) {
                $barang->update([
                    'stok' => $barang->stok + $peminjaman->jumlah_barang
                ]);
            }

            $peminjaman->delete();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus');

        } catch (\Exception $e) {
            \Log::error('Error in peminjaman destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus peminjaman: ' . $e->getMessage());
        }
    }

    public function return(Request $request, Peminjaman $peminjaman)
    {
        try {
            // Validasi tanggal kembali
            $request->validate([
                'tanggal_kembali' => 'required|date'
            ]);

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => $request->tanggal_kembali
            ]);

            // Kembalikan stok barang
            $barang = Home::where('namaBarang', $peminjaman->nama_barang)->first();
            if ($barang) {
                $barang->update([
                    'stok' => $barang->stok + $peminjaman->jumlah_barang
                ]);
            }

            return redirect()->route('peminjaman.index')
                ->with('success', 'Barang berhasil dikembalikan');

        } catch (\Exception $e) {
            \Log::error('Error in peminjaman return: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengembalikan barang: ' . $e->getMessage());
        }
    }
}

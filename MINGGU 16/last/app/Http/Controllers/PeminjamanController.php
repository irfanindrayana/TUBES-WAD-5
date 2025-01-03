<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PDF;    

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
        $peminjaman = DB::table('peminjaman')
            ->select('peminjaman.*', 'users.name as user_name')
            ->leftJoin('users', 'peminjaman.user_id', '=', 'users.id')
            ->get();
            
        $barang = DB::table('home')->get();
        $member = DB::table('members')->get();
        
        return view('peminjaman.index', compact('peminjaman', 'barang', 'member'));
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
        $barang = DB::table('home')->where('namaBarang', $request->nama_barang)->first();
        
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
                'jumlah_barang' => $request->jumlah_barang,
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('peminjaman')->insert($data);

            // Kurangi stok barang
            if ($barang) {
                DB::table('home')
                    ->where('id', $barang->id)
                    ->update(['stok' => $barang->stok - $request->jumlah_barang]);
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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required',
            'nama_barang' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jumlah_barang' => 'required|integer|min:1'
        ]);

        try {
            $peminjaman = DB::table('peminjaman')->where('id', $id)->first();
            if (!$peminjaman) {
                abort(404);
            }

            // Siapkan data update
            $updateData = [
                'nama_peminjam' => $request->nama_peminjam,
                'nama_barang' => $request->nama_barang,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jumlah_barang' => $request->jumlah_barang,
                'updated_at' => now()
            ];

            // Handle gambar
            if ($request->hasFile('gambar_baru')) {
                // Hapus gambar lama jika ada dan bukan gambar default
                if ($peminjaman->gambar && $peminjaman->gambar != 'no-image.png') {
                    $oldImagePath = public_path('storage/gambar/' . $peminjaman->gambar);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $file = $request->file('gambar_baru');
                $extension = $file->getClientOriginalExtension();
                $fileName = str_replace(' ', '_', $request->nama_barang) . '_' . time() . '.' . $extension;
                
                // Pindahkan file ke storage
                if ($file->move(public_path('storage/gambar'), $fileName)) {
                    $updateData['gambar'] = $fileName;
                    \Log::info('Gambar berhasil diupload: ' . $fileName);
                } else {
                    \Log::error('Gagal mengupload gambar');
                }
            }

            DB::table('peminjaman')
                ->where('id', $id)
                ->update($updateData);

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil diperbarui');

        } catch (\Exception $e) {
            \Log::error('Error in peminjaman update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui peminjaman: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $peminjaman = DB::table('peminjaman')->where('id', $id)->first();
            if (!$peminjaman) {
                abort(404);
            }

            // Hapus gambar jika ada dan bukan gambar default
            if ($peminjaman->gambar && $peminjaman->gambar != 'no-image.png' && 
                Storage::exists('public/gambar/' . $peminjaman->gambar)) {
                Storage::delete('public/gambar/' . $peminjaman->gambar);
            }

            // Kembalikan stok barang
            $barang = DB::table('home')->where('namaBarang', $peminjaman->nama_barang)->first();
            if ($barang) {
                DB::table('home')
                    ->where('id', $barang->id)
                    ->update(['stok' => $barang->stok + $peminjaman->jumlah_barang]);
            }

            DB::table('peminjaman')->where('id', $id)->delete();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus');

        } catch (\Exception $e) {
            \Log::error('Error in peminjaman destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus peminjaman: ' . $e->getMessage());
        }
    }

    public function return(Request $request, $id)
    {
        try {
            $peminjaman = DB::table('peminjaman')->where('id', $id)->first();
            if (!$peminjaman) {
                abort(404);
            }

            DB::table('peminjaman')
                ->where('id', $id)
                ->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now(),
                    'updated_at' => now()
                ]);
            
            // Kembalikan stok barang
            $barang = DB::table('home')->where('namaBarang', $peminjaman->nama_barang)->first();
            if ($barang) {
                DB::table('home')
                    ->where('id', $barang->id)
                    ->update(['stok' => $barang->stok + $peminjaman->jumlah_barang]);
            }

            return redirect()->route('peminjaman.index')
                ->with('success', 'Barang berhasil dikembalikan');

        } catch (\Exception $e) {
            \Log::error('Error in peminjaman return: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengembalikan barang: ' . $e->getMessage());
        }
    }

    public function invoice($id)
    {
        try {
            $peminjaman = DB::table('peminjaman')
                ->select('peminjaman.*', 'users.name as admin_name')
                ->leftJoin('users', 'peminjaman.user_id', '=', 'users.id')
                ->where('peminjaman.id', $id)
                ->first();

            if (!$peminjaman) {
                abort(404);
            }

            // Generate nomor invoice
            $tahun = date('Y');
            $bulan = date('m');
            $totalInvoiceBulanIni = DB::table('peminjaman')
                ->whereYear('tanggal_pinjam', $tahun)
                ->whereMonth('tanggal_pinjam', $bulan)
                ->count();
            
            $nomorUrut = str_pad($totalInvoiceBulanIni + 1, 3, '0', STR_PAD_LEFT);
            $nomorInvoice = $nomorUrut . '/INV/GDG/' . $bulan . '/' . $tahun;

            $data = [
                'peminjaman' => $peminjaman,
                'nomorInvoice' => $nomorInvoice,
                'tanggal' => date('d/m/Y'),
                'admin' => Auth::user()->name
            ];

            $pdf = PDF::loadView('peminjaman.invoice', $data);
            return $pdf->stream('invoice-peminjaman-' . $id . '.pdf');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function printInvoice($id)
    {
        try {
            $peminjaman = DB::table('peminjaman')
                ->select('peminjaman.*', 'users.name as admin_name')
                ->leftJoin('users', 'peminjaman.user_id', '=', 'users.id')
                ->where('peminjaman.id', $id)
                ->first();

            if (!$peminjaman) {
                abort(404);
            }

            // Generate nomor invoice
            $tahun = date('Y');
            $bulan = date('m');
            $totalInvoiceBulanIni = DB::table('peminjaman')
                ->whereYear('tanggal_pinjam', $tahun)
                ->whereMonth('tanggal_pinjam', $bulan)
                ->count();
            
            $nomorUrut = str_pad($totalInvoiceBulanIni + 1, 3, '0', STR_PAD_LEFT);
            $nomorInvoice = $nomorUrut . '/INV/GDG/' . $bulan . '/' . $tahun;

            $data = [
                'peminjaman' => $peminjaman,
                'nomorInvoice' => $nomorInvoice,
                'tanggal' => date('d/m/Y'),
                'admin' => Auth::user()->name
            ];

            $pdf = PDF::loadView('peminjaman.invoice', $data);
            return $pdf->stream('invoice-peminjaman-' . $id . '.pdf');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function searchMember(Request $request)
    {
        $query = $request->get('query');
        $members = DB::table('members')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'email', 'phone', 'address')
            ->get();
        
        return response()->json($members);
    }

    public function searchBarang(Request $request)
    {
        $query = $request->get('query');
        $barang = DB::table('home')
            ->where('namaBarang', 'LIKE', "%{$query}%")
            ->select('id', 'namaBarang', 'gambar', 'stok', 'deskripsi')
            ->get();
        
        return response()->json($barang);
    }
}

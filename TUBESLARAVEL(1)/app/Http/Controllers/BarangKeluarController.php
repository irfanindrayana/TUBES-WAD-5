<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Http\Requests\StoreBarangKeluarRequest;
use App\Http\Requests\UpdateBarangKeluarRequest;
use Illuminate\Support\Facades\DB;
use PDF;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokHampirHabis = DB::select('SELECT * FROM barang_keluars WHERE jumlah < ?', [5]);
        $barangKeluar = DB::select('SELECT * FROM barang_keluars');
        return view('barangKeluar.index', compact('stokHampirHabis', 'barangKeluar'));
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
    public function store(StoreBarangKeluarRequest $request)
    {
        try {
            // Insert data tanpa nomor invoice
            $query = "INSERT INTO barang_keluars (tanggal, nama_barang, deskripsi, jumlah) VALUES (?, ?, ?, ?)";
            DB::insert($query, [
                $request->tanggal,
                $request->nama_barang,
                $request->deskripsi,
                $request->jumlah
            ]);

            return redirect()->route('barangKeluar.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar)
    {
        // -
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $barangKeluar)
    {
        // -
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarangKeluarRequest $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'tanggal' => 'required|date',
                'nama_barang' => 'required|string',
                'deskripsi' => 'required|string',
                'jumlah' => 'required|numeric|min:1'
            ]);

            // Update data
            $query = "UPDATE barang_keluars SET tanggal = ?, nama_barang = ?, deskripsi = ?, jumlah = ?, updated_at = NOW() WHERE id = ?";
            DB::update($query, [
                $request->tanggal,
                $request->nama_barang,
                $request->deskripsi,
                $request->jumlah,
                $id
            ]);

            return redirect()->route('barangKeluar.index')->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Hapus data
            DB::delete('DELETE FROM barang_keluars WHERE id = ?', [$id]);
            
            return redirect()->route('barangKeluar.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function barangKeluarManual()
    {
        try {
            $pdf = PDF::loadView('barang_keluar.surat-jalan-manual');
            
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('surat-jalan-manual-'.date('Y-m-d').'.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak surat: ' . $e->getMessage());
        }
    }

    /**
     * Generate an invoice with data from the database.
     */
    public function barangKeluar($id)
    {
        try {
            $barangKeluar = DB::select('SELECT * FROM barang_keluars WHERE id = ?', [$id])[0];

            $pdf = PDF::loadView('barang_keluar.surat-jalan', [
                'barangKeluar' => $barangKeluar,
                'tanggal' => date('d/m/Y', strtotime($barangKeluar->tanggal)),
                'nomorSurat' => 'BK-' . date('Ymd') . '-' . $id
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('surat-jalan-'.$id.'-'.date('Y-m-d').'.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak surat: ' . $e->getMessage());
        }
    }
}

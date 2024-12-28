<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Http\Requests\StoreBarangKeluarRequest;
use App\Http\Requests\UpdateBarangKeluarRequest;
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
        $query = "INSERT INTO barang_keluars (tanggal, nama_barang, deskripsi, jumlah) VALUES (?, ?, ?, ?)";
        DB::insert($query, [$request->tanggal, $request->nama_barang, $request->deskripsi, $request->jumlah]);
        return redirect()->route('barangKeluar.index');
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
    public function update(UpdateBarangKeluarRequest $request, BarangKeluar $barangKeluar)
    {
        $query = "UPDATE barang_keluars SET tanggal = ?, nama_barang = ?, deskripsi = ?, jumlah = ? WHERE id = ?";
        DB::update($query, [$request->tanggal, $request->nama_barang, $request->deskripsi, $request->jumlah, $id]);
        return redirect()->route('barangKeluar.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::delete('DELETE FROM barang_keluars WHERE id = ?', [$id]);
        return redirect()->route('barangKeluar.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Http\Requests\StoreBarangMasukRequest;
use App\Http\Requests\UpdateBarangMasukRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
        return view('barang_masuk.index', compact('stokHampirHabis', 'barangMasuk'));
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
    public function store(StoreBarangMasukRequest $request)
    {
        //
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

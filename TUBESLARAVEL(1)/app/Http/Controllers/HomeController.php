<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;

class HomeController extends Controller
{
    public function index()
    {
        $datastok = Home::where('stok', '<', 5)->get();
        $homes = Home::all();
        return view('home.index', compact('datastok', 'homes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'namaBarang' => 'required|string',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer',
        ]);

        // Home::create([
        //     'namaBarang' => $request->namaBarang,
        //     'deskripsi' => $request->deskripsi,
        //     'stok' => $request->stok,
        // ]);

        Home::create($validatedData);

        return redirect()->route('home.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'namaBarang' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        $home = Home::findOrFail($id);
        // $home->update($request->only('namaBarang', 'deskripsi'));
        $home->update($validatedData);

        return redirect()->route('home.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $home = Home::findOrFail($id);
        $home->delete();

        return redirect()->route('home.index')->with('success', 'Barang berhasil dihapus!');
    }
}

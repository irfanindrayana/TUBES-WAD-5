<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (!session('user_id')) {
    //             return redirect()->route('login');
    //         }
    //         return $next($request);
    //     });
    // }

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
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/gambar', $namaFile); // Simpan di storage/app/public/gambar

            $validatedData['gambar'] = $namaFile; // Simpan nama file ke $validatedData
        }

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

    public function searchBarang(Request $request)
    {
        $keyword = $request->get('keyword');
        $barang = Home::where('namaBarang', 'LIKE', "%{$keyword}%")->get();
        
        return response()->json($barang);
    }
}

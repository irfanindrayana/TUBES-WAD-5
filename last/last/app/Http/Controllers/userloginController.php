<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userlogin;
use Illuminate\Support\Facades\Hash;

class userloginController extends Controller
{
    public function index()
    {
        $userlogin = login::all();

        return view('login.index', compact('userlogin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nav = 'Membuat Login';
        return view('login.create', compact('nav'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreloginRequest $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|max:100',
            'password' => 'required|string',
        ]);

        login::create($validatedData);

        return redirect()->route('login.index')->with('success', 'Berhasil Masuk.'

);
    }

    /**
     * Display the specified resource.
     */
    public function show(login $login)
    {
        $nav = 'Detail Login -' . $login->email;
        return view('login.show', compact('login', 'nav'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(login $login)
    {
        $nav = 'Edit Login - ' . $login->email;
        return view('login.edit', compact('login', 'nav'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateloginRequest $request, login $login)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|max::100',
            'password' => 'required|string',
        ]);
        $login->update($validatedData);

        return redirect()->route('login.index')->with('success', 'Login berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(login $login)
    {
        $login->delete();

        return redirect()->route('login.index')->with('success', 'Login berhasil dihapus.');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = userlogin::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login berhasil
            session(['user_id' => $user->id]);
            return redirect()->intended('/home');
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->withInput();
    }
}

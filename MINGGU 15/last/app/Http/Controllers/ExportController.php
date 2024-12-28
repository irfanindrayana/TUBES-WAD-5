<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $homes = Home::all();
        return view('stock.index', compact('homes'));
    }
}

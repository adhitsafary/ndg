<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpinWheel;

class SpinWheelController extends Controller
{
    public function index()
    {
        $options = SpinWheel::all();
        return view('spin.index', compact('options'));
    }

    //// 

    // Tambahkan method ini
    public function create()
    {
        return view('spin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'chance' => 'required|integer|min:1',
        ]);

        SpinWheel::create($request->all());
        return redirect()->route('spin.index')->with('success', 'Pilihan berhasil ditambahkan!');
    }
}

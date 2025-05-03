<?php

namespace App\Http\Controllers;

use App\Models\RegisterPelangganBaru;
use Illuminate\Http\Request;

class RegisterPelangganBaruController extends Controller
{
    public function index()
    {
        $pelanggan_baru = RegisterPelangganBaru::all();
        return view('registerpelangganbaru.index', compact('pelanggan_baru'));
    }

    public function index_pelanggan()
    {
        $pelanggan_baru = RegisterPelangganBaru::all();

        return view('registerpelangganbaru.index_pelanggan', compact('pelanggan_baru'));
    }

    public function create()
    {
        return view('registerpelangganbaru.create');
    }

    public function store(Request $request)
    {
        RegisterPelangganBaru::create($request->all());
        return redirect()->back()->with('success', 'Pendaptaran berhasil di lakukan, langkah selanjutnya tunggu admin kami chat melalui whatsapp');
    }


    public function edit($id)
    {
        $pelanggan_baru = RegisterPelangganBaru::findOrFail($id);
        return view('registerpelangganbaru.edit', compact('pelanggan_baru'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = RegisterPelangganBaru::findOrFail($id);
        $pelanggan->update($request->all());
        return redirect()->route('registerpelangganbaru.index')->with('success', 'Pendaptaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        RegisterPelangganBaru::findOrFail($id)->delete();
        return redirect()->route('registerpelangganbaru.index')->with('success', 'Pendaptaran berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AdapterModel;
use Illuminate\Http\Request;

class AdapterController extends Controller
{
    public function detail($id)
    {
        $adapter = AdapterModel::findOrFail($id);
        return view('modem.adapter.detail', compact('adapter'));
    }


    public function index(Request $request)
    {
        $query = AdapterModel::query();

        if ($request->has('search')) {
            $query->where('keterangan', 'LIKE', '%' . $request->search . '%');
        }

        $adapter = $query->get();


        return view('modem.adapter.index', compact('adapter'));
    }

    public function create()
    {


        return view('modem.adapter.create');
    }



    public function store(Request $request)
    {

        $adapter = new AdapterModel();

        // Isi data adapter
        $adapter->tanggal = $request->tanggal;
        $adapter->kode_barang = $request->kode_barang;

        $adapter->pic = $request->pic;
        $adapter->petugas = $request->petugas;
        $adapter->keterangan = $request->keterangan;

        // Simpan data adapter ke database
        $adapter->save();

        // Redirect ke halaman adapter index setelah penyimpanan berhasil
        return redirect()->route('adapter.index')->with('success', 'Data adapter berhasil disimpan.');
    }



    public function show(string $id) {

    }


    public function edit(string $id)
    {
        $adapter = AdapterModel::findOrFail($id);
        return view('modem.adapter.edit', compact('adapter'));
    }


    public function update(Request $request, string $id)
    {
        $adapter = AdapterModel::findOrFail($id);

        $adapter->jumlah = $request->jumlah;
        $adapter->keterangan = $request->keterangan;

        $adapter->save();

        return redirect()->route('modem.adapter.index');
    }


    public function destroy(string $id)
    {
        $adapter = AdapterModel::findOrFail($id);
        $adapter->delete();

        return redirect()->route('modem.adapter.index');
    }
}

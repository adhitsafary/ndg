<?php

namespace App\Http\Controllers;

use App\Models\Modem;
use Illuminate\Http\Request;

class ModemController extends Controller
{
    public function index(Request $request)
    {
        $query = Modem::query();

        if ($request->has('search')) {
            $query->where('user', 'LIKE', '%' . $request->search . '%');
        }

        $modem = $query->get();


        return view('modem.index', compact('modem'));
    }

    public function index_hp(Request $request)
    {
        $query = Modem::query();

        if ($request->has('search')) {
            $query->where('user', 'LIKE', '%' . $request->search . '%');
        }

        $modem = $query->get();


        return view('modem.index_hp', compact('modem'));
    }

    public function create()
    {
        return view('modem.create');
    }


    public function store(Request $request)
    {
        $modem = new Modem();

        // Validasi data input
        $request->validate([
            'sn_modem' => 'required|string',
            'model' => 'required|string',
            'tgl_keluar' => 'nullable|string',
            'user' => 'nullable|string',
            'id_mikrotik' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Isi data modem
        $modem->sn_modem = $request->sn_modem;
        $modem->model = $request->model;
        $modem->tgl_keluar = $request->tgl_keluar; // Tanggal dan waktu
        $modem->user = $request->user; // Bisa null
        $modem->id_mikrotik = $request->id_mikrotik; // Bisa null
        $modem->keterangan = $request->keterangan;

        // Simpan data modem ke database
        $modem->save();

        return redirect()->route('modem.index')->with('success', 'Data modem berhasil disimpan.');
    }






    public function show(string $id) {}


    public function edit(string $id)
    {
        $modem = Modem::findOrFail($id);
        return view('modem.edit', compact('modem'));
    }


    public function update(Request $request, string $id)
    {
        $modem = Modem::findOrFail($id);

        $modem->sn_modem = $request->sn_modem;
        $modem->model = $request->model;
        $modem->tgl_keluar = $request->tgl_keluar;
        $modem->user = $request->user;
        $modem->id_mikrotik = $request->id_mikrotik;
        $modem->keterangan = $request->keterangan;


        $modem->save();

        return redirect()->route('modem.index');
    }


    public function destroy(string $id)
    {
        $modem = Modem::findOrFail($id);
        $modem->delete();

        return redirect()->route('modem.index');
    }
}

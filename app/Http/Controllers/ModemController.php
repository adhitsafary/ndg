<?php

namespace App\Http\Controllers;

use App\Models\Modem;
use Illuminate\Http\Request;

class ModemController extends Controller
{
    public function index(Request $request)
    {
        $query = Modem::query();


        $query->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $query->where('user', 'LIKE', '%' . $request->search . '%');
        }

        $modem = $query->get();


        return view('modem.index', compact('modem'));
    }

    public function index_hp(Request $request)
    {
        $query = Modem::query();
        $query->orderBy('created_at', 'desc');


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


    public function store2(Request $request)
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

        return redirect()->route('modem_hp.index')->with('success', 'Data modem berhasil disimpan.');
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

        // Ekstraksi SN Modem hanya mengambil angka/huruf setelah "SN:" atau "&sn="
        $sn_modem = $request->sn_modem;
        if (preg_match('/SN:([A-Za-z0-9]+)/', $sn_modem, $matches) || preg_match('/&sn=([A-Za-z0-9]+)/', $sn_modem, $matches)) {
            $sn_modem = $matches[1];
        }

        // Isi data modem
        $modem->sn_modem = $sn_modem;
        $modem->model = $request->model;
        $modem->tgl_keluar = $request->tgl_keluar; // Tanggal dan waktu
        $modem->user = $request->user; // Bisa null
        $modem->id_mikrotik = $request->id_mikrotik; // Bisa null
        $modem->keterangan = $request->keterangan;

        // Simpan data modem ke database
        $modem->save();

        return redirect()->route('modem_hp.index')->with('success', 'Data modem berhasil disimpan.');
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

        return redirect()->route('modem_hp.index');
    }


    public function destroy(string $id)
    {
        $modem = Modem::findOrFail($id);
        $modem->delete();

        return redirect()->route('modem_hp.index');
    }
}

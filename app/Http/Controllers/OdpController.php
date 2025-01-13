<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Odp;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;


class OdpController extends Controller
{
    // Menampilkan daftar ODP
    public function index()
    {
        $odps = Odp::leftJoin('pelanggan', 'odp.nama_odp', '=', 'pelanggan.odp')
            ->select('odp.id', 'odp.nama_odp', 'odp.jml_port', 'odp.no_urut_odp', DB::raw('COUNT(pelanggan.odp) as jumlah_pelanggan'))
            ->groupBy('odp.id', 'odp.nama_odp', 'odp.jml_port', 'odp.no_urut_odp') // Tambahkan 'odp.jml_port' ke groupBy
            ->get();

        return view('odp.index', compact('odps'));
    }


    // Menampilkan form untuk membuat ODP baru
    public function create()
    {
        return view('odp.create');
    }

    // Menyimpan data ODP baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_odp' => 'required|string|max:255',
            'jml_port' => 'required|integer',
            'longitude' => 'required|string',
            'latitude' => 'required|string',
            'no_urut_odp' => 'required|string',
        ]);

        Odp::create([
            'nama_odp' => $request->nama_odp,
            'jml_port' => $request->jml_port,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'no_urut_odp' => $request->no_urut_odp,
        ]);

        return redirect()->route('odp.index')->with('success', 'ODP baru berhasil ditambahkan!');
    }

    // Menampilkan detail ODP
    public function show($nama_odp)
    {
        $pelanggans = Pelanggan::where('odp', $nama_odp)->get();
        return view('odp.show', compact('pelanggans', 'nama_odp'));
    }

    // Menampilkan form untuk mengedit ODP
    public function edit($id)
    {
        $odp = Odp::findOrFail($id);
        return view('odp.edit', compact('odp'));
    }

    // Memperbarui data ODP
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_odp' => 'required|string|max:255',
            'jml_port' => 'required|integer',
            'longitude' => 'required|string',
            'latitude' => 'required|string',
            'no_urut_odp' => 'required|string',
        ]);

        $odp = Odp::findOrFail($id);
        $odp->update([
            'nama_odp' => $request->nama_odp,
            'jml_port' => $request->jml_port,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'no_urut_odp' => $request->no_urut_odp,
        ]);

        return redirect()->route('odp.index')->with('success', 'ODP berhasil diperbarui.');
    }

    // Menghapus ODP
    public function destroy($id)
    {
        $odp = Odp::findOrFail($id);
        $odp->delete();

        return redirect()->route('odp.index')->with('success', 'ODP berhasil dihapus.');
    }
}

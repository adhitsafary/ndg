<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Odp;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;


class OdpController extends Controller
{

    public function index(Request $request)
    {
        // Ambil input pencarian
        $search = $request->input('search');

        // Query dengan kondisi pencarian
        $odps = Odp::leftJoin('pelanggan', 'odp.kode_odp', '=', 'pelanggan.odp')
            ->select('odp.id', 'odp.kode_odp', 'odp.jml_port', 'odp.no_urut_odp', 'odp.kecamatan', 'odp.desa', 'odp.dusun', 'odp.jml_odp', DB::raw('COUNT(pelanggan.odp) as jumlah_pelanggan'))
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('odp.kecamatan', 'like', "%$search%")
                        ->orWhere('odp.desa', 'like', "%$search%")
                        ->orWhere('odp.dusun', 'like', "%$search%")
                        ->orWhere('odp.jml_odp', 'like', "%$search%")
                        ->orWhere('odp.kode_odp', 'like', "%$search%");
                });
            })
            ->groupBy('odp.id', 'odp.kode_odp', 'odp.jml_port', 'odp.no_urut_odp', 'odp.kecamatan', 'odp.desa', 'odp.dusun', 'odp.jml_odp')
            ->get();

        return view('odp.index', compact('odps', 'search'));
    }

    // Menampilkan daftar ODP
    public function index1(Request $request)
    {



        $odps = Odp::leftJoin('pelanggan', 'odp.kode_odp', '=', 'pelanggan.odp')
            ->select('odp.id', 'odp.kode_odp', 'odp.jml_port', 'odp.no_urut_odp', 'odp.kecamatan', 'odp.desa', 'odp.dusun', 'odp.jml_odp',  DB::raw('COUNT(pelanggan.odp) as jumlah_pelanggan'))
            ->groupBy('odp.id', 'odp.kode_odp', 'odp.jml_port', 'odp.no_urut_odp', 'odp.kecamatan', 'odp.desa', 'odp.dusun', 'odp.jml_odp')
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
            'kecamatan' => 'nullable|string',
            'desa' => 'nullable|string',
            'dusun' => 'nullable|string',
            'jml_odp' => 'nullable|string',

            'kode_odp' => 'required|string|max:255',
            'jml_port' => 'required|integer',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
            'no_urut_odp' => 'required|string',
        ]);

        Odp::create([

            'kecamatan' => $request->kecamatan,
            'desa' => $request->desa,
            'dusun' => $request->dusun,
            'jml_odp' => $request->jml_odp,
            'kode_odp' => $request->kode_odp,
            'jml_port' => $request->jml_port,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'no_urut_odp' => $request->no_urut_odp,
        ]);

        return redirect()->route('odp.index')->with('success', 'ODP baru berhasil ditambahkan!');
    }

    // Menampilkan detail ODP
    public function show($kode_odp)
    {
        // Ambil data pelanggan berdasarkan kode ODP
        $pelanggans = Pelanggan::where('odp', $kode_odp)->get();

        // Ambil detail ODP berdasarkan kode ODP
        $odpDetails = Odp::where('kode_odp', $kode_odp)->first();

        // Jika ODP tidak ditemukan, tampilkan halaman 404
        if (!$odpDetails) {
            abort(404, 'Data ODP tidak ditemukan.');
        }

        // Kirim data ke view
        return view('odp.show', [
            'pelanggans' => $pelanggans,
            'kode_odp' => $kode_odp,
            'kecamatan' => $odpDetails->kecamatan,
            'desa' => $odpDetails->desa,
            'dusun' => $odpDetails->dusun,
            'jml_odp' => $odpDetails->jml_odp,
        ]);
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
            'kecamatan' => 'nullable|string',
            'desa' => 'nullable|string',
            'dusun' => 'nullable|string',
            'jml_odp' => 'nullable|string',

            'kode_odp' => 'required|string|max:255',
            'jml_port' => 'required|integer',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
            'no_urut_odp' => 'required|string',
        ]);

        $odp = Odp::findOrFail($id);
        $odp->update([
            'kecamatan' => $request->kecamatan,
            'desa' => $request->desa,
            'dusun' => $request->dusun,
            'jml_odp' => $request->jml_odp,

            'kode_odp' => $request->kode_odp,
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

<?php

namespace App\Http\Controllers;

use App\Models\GeneratorId;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneratorIdController extends Controller
{
    public function index()
    {
        $generatorIds = GeneratorId::all();
        return view('generator_id.index', compact('generatorIds'));
    }

    public function index_hp()
    {
        $generatorIds = GeneratorId::all();
        return view('generator_id.index_hp', compact('generatorIds'));
    }

    public function create()
    {
        $pelanggan = DB::table('pelanggan')->get('id', 'paket_plg', 'kode_odp');
        return view('generator_id.create', compact('pelanggan'));
    }

    public function store2(Request $request)
    {
        $request->validate([
            'kode_perusahaan' => 'required|max:100',
            'kode_paket_plg' => 'required|max:100',
            'kode_nik' => 'required|max:100',
            'kode_odp' => 'required|max:100',
            'nama_plg' => 'required|max:100',
            'kode_unik' => 'nullable|max:100',
        ]);

        // Membuat kode_unik dengan gabungan yang diinginkan
        $kodeUnik = $request->kode_perusahaan .
            substr($request->kode_nik, 8, 4) .
            substr($request->kode_odp, 0, 3) .
            $request->kode_paket_plg .
            '@net.net';

        // Menambahkan kode_unik ke dalam data yang akan disimpan
        $data = $request->all();
        $data['kode_unik'] = $kodeUnik;

        // Membuat data baru di database
        $generatorId = GeneratorId::create($data);

        // Mengupdate atau mengirim data ke tabel pelanggan
        // Asumsi pelanggan menggunakan nik sebagai referensi dan mengupdate kode_unik
        $pelanggan = Pelanggan::where('nik', $request->kode_nik)->first();

        if ($pelanggan) {
            // Update pelanggan dengan kode_unik yang baru
            $pelanggan->kode_unik = $kodeUnik;
            $pelanggan->save();
        } else {
            // Jika pelanggan tidak ditemukan, kamu bisa membuat record baru jika perlu
            Pelanggan::create([
                'nik' => $request->kode_nik,
                'kode_unik' => $kodeUnik,
                // tambah atribut lainnya sesuai kebutuhan
            ]);
        }

        return redirect()->route('generator_id.index')
            ->with('success', 'Generator ID created and pelanggan updated successfully.');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_perusahaan' => 'required|max:100',
            'kode_paket_plg' => 'required|max:100',
            'kode_nik' => 'required|max:100',
            'kode_odp' => 'required|max:100',
            'nama_plg' => 'required|max:100',
            'id_plg' => 'required|exists:pelanggan,id_plg', // Validasi ID pelanggan
        ]);

        // Membuat kode_unik dengan gabungan yang diinginkan
        $kodeUnik = $request->kode_perusahaan .
            substr($request->kode_nik, 8, 4) .
            substr($request->kode_odp, 0, 3) .
            $request->kode_paket_plg .
            '@net.net';

        // Menambahkan kode_unik ke dalam data yang akan disimpan
        $data = $request->all();
        $data['kode_unik'] = $kodeUnik;

        // Mulai debugging log untuk melihat data yang dikirim
        Log::info('Data yang dikirim:', $request->all());

        try {
            // Membuat data baru di tabel generator_id
            GeneratorId::create($data);

            // Cari pelanggan berdasarkan id_plg
            $pelanggan = Pelanggan::find($request->id_plg);

            // Debugging log untuk melihat pelanggan yang ditemukan
            if ($pelanggan) {
                Log::info('Pelanggan ditemukan:', ['id_plg' => $request->id_plg]);
            } else {
                Log::error('Pelanggan tidak ditemukan dengan ID:', ['id_plg' => $request->id_plg]);
            }

            if ($pelanggan) {
                // Update data pelanggan dengan kode_unik dan nik baru
                $pelanggan->kode_unik = $kodeUnik;
                $pelanggan->nik = $request->kode_nik;
                $pelanggan->save();  // Simpan perubahan

                // Mengirimkan pesan sukses
                return redirect()->route('generator_id.index')
                    ->with('success', 'Generator ID created and pelanggan updated successfully.');
            } else {
                // Jika pelanggan tidak ditemukan, tampilkan pesan error
                return redirect()->route('generator_id.index')
                    ->with('error', 'Pelanggan tidak ditemukan dengan ID ' . $request->id_plg);
            }
        } catch (\Exception $e) {
            // Menangani error dan mencatatnya
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());

            return redirect()->route('generator_id.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }




    public function edit(GeneratorId $generatorId)
    {
        return view('generator_id.edit', compact('generatorId'));
    }

    public function update(Request $request, GeneratorId $generatorId)
    {
        $request->validate([
            'kode_perusahaan' => 'required|max:100',
            'kode_paket_plg' => 'required|max:100',
            'kode_nik' => 'required|max:100',
            'kode_odp' => 'required|max:100',
            'nama_plg' => 'required|max:100',
            'kode_unik' => 'nulable|max:100',

        ]);

        $generatorId->update($request->all());

        return redirect()->route('generator_id.index')
            ->with('success', 'Generator ID updated successfully.');
    }



    public function destroy(string $id)
    {
        $modem = GeneratorId::findOrFail($id);
        $modem->delete();

        return redirect()->route('generator_id.index')
            ->with('success', 'Generator ID deleted successfully.');
    }
}

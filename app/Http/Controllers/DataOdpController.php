<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataOdp;

class DataOdpController extends Controller
{
    // Index method to show the data and allow CRUD operations
    public function index(Request $request)
    {
        $data_odp = DataOdp::orderBy('created_at', 'desc')->paginate(1000)->appends($request->all());
        return view('data-odp.index', compact('data_odp'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192', // Maksimal 8MB
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'tipe.required' => 'Tipe wajib diisi.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto yang diizinkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran foto maksimal adalah 8MB.',
        ]);


        try {
            // Upload file
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'asset/img/odp';
            $file->move(public_path($path), $filename);

            // Generate Maps URL jika tidak diisi manual
            $maps = $request->maps;
            if (!$maps && $request->latitude && $request->longitude) {
                $maps = "https://www.google.com/maps?q={$request->latitude},{$request->longitude}";
            }

            // Simpan ke database
            DataOdp::create([
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'foto' => $path . '/' . $filename,
                'maps' => $maps,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return redirect()->route('data-odp.index')->with('success', 'Data ODP berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan data: ' . $e->getMessage()]);
        }
    }




    // Store new ODP data
    public function store2(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'tipe.required' => 'Tipe wajib diisi.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto yang diizinkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
            'latitude.required' => 'Latitude wajib diisi.',
            'longitude.required' => 'Longitude wajib diisi.',
        ]);

        try {
            // Simpan foto ke direktori public/asset/img/odp
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik
            $path = 'asset/img/odp';
            $file->move(public_path($path), $filename); // Simpan ke folder public

            // Simpan data baru ke database
            DataOdp::create([
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'foto' => $path . '/' . $filename, // Simpan path foto ke database
                'maps' => "https://www.google.com/maps?q={$request->latitude},{$request->longitude}",
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return redirect()->route('data-odp.index')->with('success', 'Data ODP berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan data. Silakan coba lagi.');
        }
    }


    // Edit method
    public function edit($id)
    {
        $data_odp = DataOdp::findOrFail($id);
        return view('data-odp.edit', compact('data_odp'));
    }


    // Update method
    public function update(Request $request, $id)
    {
        $data_odp = DataOdp::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'maps' => 'required|string|max:255',
            'tipe' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($data_odp->foto && file_exists(public_path($data_odp->foto))) {
                    unlink(public_path($data_odp->foto));
                }

                // Simpan foto baru
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = 'asset/img/odp';
                $file->move(public_path($path), $filename);

                $data_odp->foto = $path . '/' . $filename;
            }

            $data_odp->update([
                'nama' => $request->nama,
                'maps' => $request->maps,
                'tipe' => $request->tipe,
            ]);

            return redirect()->route('data-odp.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Delete method
    public function destroy($id)
    {
        $odp = DataOdp::findOrFail($id);
        $odp->delete();
        return redirect()->route('data-odp.index')->with('success', 'Data berhasil dihapus!');
    }
}

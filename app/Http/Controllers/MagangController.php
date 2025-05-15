<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MagangController extends Controller
{
    public function index()
    {
        $data = Magang::all();
        return view('magang.index', compact('data'));
    }

    public function create()
    {
        return view('magang.create');
    }

    //// 

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'judul' => 'required',
            'tanggal' => 'required',
            'dekripsi' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Pastikan folder penyimpanan ada
        $folderPath = 'asset/img/foto_magang';
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Buat nama file unik
        $filename = time() . '_' . Str::slug(pathinfo($request->file('foto')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->file('foto')->getClientOriginalExtension();

        // Simpan file ke storage/app/public/asset/img/foto_magang
        $path = $request->file('foto')->storeAs($folderPath, $filename, 'public');

        // Simpan ke database
        Magang::create([
            'nama' => $request->nama,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'dekripsi' => $request->dekripsi,
            'foto' => $path, // path relatif untuk asset('storage/'.$path)
        ]);

        return redirect()->route('magang.index')->with('success', 'Data berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $data = Magang::findOrFail($id);
        return view('magang.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Magang::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'judul' => 'required',
            'tanggal' => 'required',
            'dekripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('asset/img/foto_magang', 'public');
            $data->foto = $fotoPath;
        }

        $data->update([
            'nama' => $request->nama,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'dekripsi' => $request->dekripsi,
            'foto' => $data->foto
        ]);

        return redirect()->route('magang.index')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id)
    {
        $data = Magang::findOrFail($id);
        $data->delete();
        return redirect()->route('magang.index')->with('success', 'Data berhasil dihapus!');
    }
}

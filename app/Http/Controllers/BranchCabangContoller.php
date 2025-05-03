<?php

namespace App\Http\Controllers;

use App\Models\BranchCabangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchCabangContoller extends Controller
{
    public function index()
    {
        $branch_cabang = BranchCabangModel::all();
        return view('branch_cabang.index', compact('branch_cabang'));
    }

    public function create()
    {
        return view('branch_cabang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cabang' => 'required',
            'nama_cabang' => 'required',
            'nama_pemilik' => 'required',
            'alamat' => 'required',
            'tanggal_bergabung' => 'required|date',
            'Kepemilikan' => 'required',
            'persentase' => 'required|integer',
        ]);

        BranchCabangModel::create($request->all());
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cabang = BranchCabangModel::findOrFail($id);
        return view('branch_cabang.edit', compact('cabang'));
    }

    public function update(Request $request, $id)
    {
        $cabang = BranchCabangModel::findOrFail($id);
        $cabang->update($request->all());
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        BranchCabangModel::destroy($id);
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }

    public function pelangganDetail(Request $request, $kode_cabang)
    {
        $query = DB::table('pelanggan')
            ->where('kode_cabang', $kode_cabang);

        if ($request->filled('nama')) {
            $query->where('nama_plg', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('paket')) {
            $query->where('paket_plg', 'like', '%' . $request->paket . '%');
        }

        $pelanggan = $query->get();

        return view('branch_cabang.pelanggan_detail', compact('pelanggan', 'kode_cabang'));
    }
}

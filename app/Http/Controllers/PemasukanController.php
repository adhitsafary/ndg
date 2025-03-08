<?php

namespace App\Http\Controllers;

use App\Exports\PemasukanExport;
use App\Models\KasbonModel;
use App\Models\NetDigitalGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\PemasukanModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemasukanController extends Controller
{

    public function home()
    {

        $pemasukan = PemasukanModel::all();

        // Kirim data ke view

        return view('pemasukan.index', compact('pemasukan'));
    }


    public function detail($id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);
        return view('pemasukan.detail', compact('pemasukan'));
    }


    public function index(Request $request)
    {
        $query = PemasukanModel::query();



        $pemasukan = $query->get();
        $pemasukan = PemasukanModel::orderBy('kategori')->get();

        if ($request->has('search')) {
            $query->where('deskripsi', 'LIKE', '%' . $request->search . '%');
        }


        $totalBulanan = PemasukanModel::all();
        $totalJumlah = $totalBulanan->sum('harga_total');
        $totalBulanan = PemasukanModel::orderBy('kategori')->get();




        return view('pemasukan.index', compact('pemasukan', 'totalBulanan', 'totalJumlah'));
    }

    public function create()
    {


        return view('pemasukan.create');
    }




    public function show(string $id) {}


    public function edit(string $id_plg)
    {
        $pemasukan = PemasukanModel::findOrFail($id_plg);
        return view('pemasukan.edit', compact('pemasukan'));
    }




    public function destroy(string $id_plg)
    {
        $pemasukan = PemasukanModel::findOrFail($id_plg);
        $pemasukan->delete();

        return redirect()->route('pemasukan.index');
    }


    public function index_jml(Request $request)
    {
        $query = PemasukanModel::query();

        if ($request->has('search')) {
            $query->where('keterangan', 'LIKE', '%' . $request->search . '%');
        }


        $totalBulanan = PemasukanModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $totalJumlah = $totalBulanan->sum('harga_total');
        $totalBulanan = PemasukanModel::orderBy('kategori')->get();


        return view('pemasukan.index_jml', compact('totalBulanan', 'totalJumlah'));
    }





    public function exportExcel()
    {
        return Excel::download(new PemasukanModel(), 'pemasukan.xlsx');
    }

    public function exportPdf()
    {
        $totalBulanan = PemasukanModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $totalJumlah = $totalBulanan->sum('jumlah');

        $pdf = Pdf::loadView('pemasukan.export_pdf', compact('totalBulanan', 'totalJumlah'));

        return $pdf->download('pemasukan.pdf');
    }



    public function store(Request $request)
    {

        $request->validate([
            'deskripsi' => 'required|string',
            'harga_satuan' => 'required|string|max:255',
            'volume' => 'nullable|string',
            'harga_total' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);


        $pemasukan = new PemasukanModel();

        // Isi data pemasukan
        $pemasukan->deskripsi = $request->deskripsi;
        $pemasukan->harga_satuan = $request->harga_satuan;
        $pemasukan->volume = $request->volume;
        $pemasukan->harga_total = $request->harga_total;
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->kategori = $request->kategori;

        // Simpan data pemasukan ke database
        $pemasukan->save();

        // Redirect ke halaman pemasukan index setelah penyimpanan berhasil
        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil disimpan.');
    }


    public function update(Request $request, string $id_plg)
    {
        $request->validate([
            'deskripsi' => 'required|string',
            'harga_satuan' => 'required|string|max:255',
            'volume' => 'nullable|string',
            'harga_total' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $pemasukan = PemasukanModel::findOrFail($id_plg);

        // Perbarui data pemasukan
        $pemasukan->deskripsi = $request->deskripsi;
        $pemasukan->harga_satuan = $request->harga_satuan;
        $pemasukan->volume = $request->volume;
        $pemasukan->harga_total = $request->harga_total;
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->kategori = $request->kategori;

        // Simpan perubahan ke database
        $pemasukan->save();

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\KasbonModel;
use App\Models\NetDigitalGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengeluaranController extends Controller
{

    public function home()
    {

        $pengeluaran = PengeluaranModel::all();

        // Kirim data ke view

        return view('pengeluaran.index', compact('pengeluaran'));
    }

    public function detail($id)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id);
        return view('pengeluaran.detail', compact('pengeluaran'));
    }

    public function index(Request $request)
    {
        $query = PengeluaranModel::query();


        $pengeluaran = $query->get();
        $pengeluaran = PengeluaranModel::orderBy('kategori')->get();

        if ($request->has('search')) {
            $query->where('deskripsi', 'LIKE', '%' . $request->search . '%');
        }

        $totalBulanan = PengeluaranModel::all();
        $totalJumlah = $totalBulanan->sum('harga_total');
        $totalBulanan = PengeluaranModel::orderBy('kategori')->get();


        return view('pengeluaran.index', compact('pengeluaran', 'totalBulanan', 'totalJumlah'));
    }

    public function create()
    {

        return view('pengeluaran.create');
    }

    public function store2(Request $request)
    {

        $pengeluaran = new PengeluaranModel();

        // Isi data pengeluaran
        $pengeluaran->keterangan = $request->keterangan; // Nama dari form input
        $pengeluaran->jumlah = $request->jumlah;
        $pengeluaran->keterangan = $request->keterangan;

        // Simpan data pengeluaran ke database
        $pengeluaran->save();

        // Redirect ke halaman pengeluaran index setelah penyimpanan berhasil
        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil disimpan.');
    }

    public function show(string $id) {}

    public function edit(string $id_plg)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id_plg);
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update2(Request $request, string $id_plg)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id_plg);

        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->jumlah = $request->jumlah;
        $pengeluaran->keterangan = $request->keterangan;

        $pengeluaran->save();

        return redirect()->route('pengeluaran.index');
    }

    public function destroy(string $id_plg)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id_plg);
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index');
    }

    public function index_jml(Request $request)
    {
        $query = PengeluaranModel::query();

        if ($request->has('search')) {
            $query->where('keterangan', 'LIKE', '%' . $request->search . '%');
        }
        // Ambil hanya data bulan ini
        $pengeluaran = PengeluaranModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('kategori')
            ->get();

        // Hitung total bulan ini
        $totalJumlah = $pengeluaran->sum('harga_total');

        return view('pengeluaran.index_jml', compact('pengeluaran', 'totalJumlah'));
    }

    public function exportExcel()
    {
        return Excel::download(new PengeluaranModel(), 'pengeluaran.xlsx');
    }

    public function exportPdf()
    {
        $totalBulanan = PengeluaranModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $totalJumlah = $totalBulanan->sum('jumlah');

        $pdf = Pdf::loadView('pengeluaran.export_pdf', compact('totalBulanan', 'totalJumlah'));

        return $pdf->download('pengeluaran.pdf');
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

        $pengeluaran = new PengeluaranModel();

        // Isi data pengeluaran
        $pengeluaran->deskripsi = $request->deskripsi;
        $pengeluaran->harga_satuan = $request->harga_satuan;
        $pengeluaran->volume = $request->volume;
        $pengeluaran->harga_total = $request->harga_total;
        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->kategori = $request->kategori;

        // Simpan data pengeluaran ke database
        $pengeluaran->save();

        // Buat log aktivitas setelah penyimpanan
        $logData = [
            'deskripsi' => $pengeluaran->deskripsi,
            'keterangan' => $pengeluaran->keterangan,
            'harga_total' => $pengeluaran->harga_total,
            'harga_satuan' => $pengeluaran->harga_satuan,
            'volume' => $pengeluaran->volume,
            'kategori' => $pengeluaran->kategori,
            'created_by' => Auth::user()->name ?? 'Guest',
        ];

        logActivity('Tambah data pengeluaran', 'Pengeluaran', $logData);

        // Redirect ke halaman pengeluaran index setelah penyimpanan berhasil
        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil disimpan.');
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
        $pengeluaran = PengeluaranModel::findOrFail($id_plg);
        // Perbarui data pengeluaran
        $pengeluaran->deskripsi = $request->deskripsi;
        $pengeluaran->harga_satuan = $request->harga_satuan;
        $pengeluaran->volume = $request->volume;
        $pengeluaran->harga_total = $request->harga_total;
        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->kategori = $request->kategori;
        // Simpan perubahan ke database
        $pengeluaran->save();

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }
}

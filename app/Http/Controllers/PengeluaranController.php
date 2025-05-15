<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\KasbonModel;
use App\Models\NetDigitalGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;
use App\Models\RekapPemasanganModel;
use App\Models\X100c;
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

    public function makan2(Request $request)
    {
        $nama = $request->input('nama');

        // Query data orang berdasarkan tanggal hari ini
        $orangQuery = X100c::whereDate('created_at', Carbon::today());
        if ($nama) {
            $orangQuery->where('nama', $nama);
        }
        $orang = $orangQuery->get();

        // Query daftar nama
        $daftarNama = X100c::select('nama')->distinct()->get();

        // Query pengeluaran hari ini
        $pengeluaranHariIni = PengeluaranModel::whereDate('created_at', Carbon::today())->get();
        // Query transaksi CASH hari ini
        $cashHariIni = BayarPelanggan::whereDate('created_at', Carbon::today())
            ->where('metode_transaksi', 'CASH')->get();
        // Query transaksi TF hari ini
        $tfHariIni = BayarPelanggan::whereDate('created_at', Carbon::today())
            ->where('metode_transaksi', 'TF')->get();

        // Hitung total pengeluaran hari ini
        $totalHarian = $pengeluaranHariIni->sum('harga_total');

        // Hitung total pemasukan dari CASH dan TF
        $totalCash = $cashHariIni->sum('jumlah_pembayaran');
        $totalTf = $tfHariIni->sum('jumlah_pembayaran');

        // Buat data cashflow sebagai collection gabungan dari pengeluaran dan pemasukan
        $cashflow = collect();

        // Masukkan pengeluaran (debit) ke dalam cashflow
        foreach ($pengeluaranHariIni as $item) {
            $cashflow->push((object)[
                'nama' => $item->nama_pengeluaran,
                'tipe' => 'debit',
                'jumlah' => $item->harga_total,
                'tanggal' => $item->created_at->format('Y-m-d'),
                'keterangan' => $item->keterangan,
            ]);
        }

        // Masukkan transaksi CASH (credit) ke dalam cashflow
        foreach ($cashHariIni as $item) {
            $cashflow->push((object)[
                'nama' => $item->nama_plg,
                'tipe' => 'credit',
                'jumlah' => $item->jumlah_pembayaran,
                'tanggal' => $item->created_at->format('Y-m-d'),
                'keterangan' => 'Pembayaran (CASH)',
            ]);
        }

        // Masukkan transaksi TF (credit) ke dalam cashflow
        foreach ($tfHariIni as $item) {
            $cashflow->push((object)[
                'nama' => $item->nama_plg,
                'tipe' => 'credit',
                'jumlah' => $item->jumlah_pembayaran,
                'tanggal' => $item->created_at->format('Y-m-d'),
                'keterangan' => 'Pembayaran (TF)',
            ]);
        }

        return view('pengeluaran.makan', compact(
            'orang',
            'daftarNama',
            'nama',
            'pengeluaranHariIni',
            'cashHariIni',
            'tfHariIni',
            'totalHarian',
            'totalCash',
            'totalTf',
            'cashflow'
        ));
    }

    public function makan(Request $request)
    {
        $nama = $request->input('nama');

        // Query data orang berdasarkan tanggal hari ini
        $orangQuery = X100c::whereDate('created_at', Carbon::today());
        if ($nama) {
            $orangQuery->where('nama', $nama);
        }
        $orang = $orangQuery->get();

        // Query daftar nama
        $daftarNama = X100c::select('nama')->distinct()->get();

        // Query pengeluaran hari ini
        $pengeluaranHariIni = PengeluaranModel::whereDate('created_at', Carbon::today())->get();
        // Query transaksi CASH hari ini
        $cashHariIni = BayarPelanggan::whereDate('created_at', Carbon::today())
            ->where('metode_transaksi', 'CASH')->get();
        // Query transaksi TF hari ini
        $tfHariIni = BayarPelanggan::whereDate('created_at', Carbon::today())
            ->where('metode_transaksi', 'TF')->get();

        // Query rekap pemasangan hari ini
        $rekapPemasanganHariIni = RekapPemasanganModel::whereDate('tgl_aktivasi', Carbon::today())->get();

        // Hitung total pengeluaran hari ini
        $totalHarian = $pengeluaranHariIni->sum('harga_total');

        // Hitung total pemasukan dari CASH dan TF
        $totalCash = $cashHariIni->sum('jumlah_pembayaran');
        $totalTf = $tfHariIni->sum('jumlah_pembayaran');

        // Buat data cashflow sebagai collection gabungan dari pengeluaran dan pemasukan
        $cashflow = collect();

        // Masukkan pengeluaran (debit) ke dalam cashflow
        foreach ($pengeluaranHariIni as $item) {
            $cashflow->push((object)[
                'nama' => $item->nama_pengeluaran,
                'tipe' => 'debit',
                'jumlah' => $item->harga_total,
                'tanggal' => $item->created_at->format('Y-m-d'),
                'keterangan' => $item->keterangan,
            ]);
        }

        // Masukkan transaksi CASH (credit) ke dalam cashflow
        foreach ($cashHariIni as $item) {
            $cashflow->push((object)[
                'nama' => $item->nama_plg,
                'tipe' => 'credit',
                'jumlah' => $item->jumlah_pembayaran,
                'tanggal' => $item->created_at->format('Y-m-d'),
                'keterangan' => 'Pembayaran (CASH)',
            ]);
        }

        // Masukkan transaksi TF (credit) ke dalam cashflow
        foreach ($tfHariIni as $item) {
            $cashflow->push((object)[
                'nama' => $item->nama_plg,
                'tipe' => 'credit',
                'jumlah' => $item->jumlah_pembayaran,
                'tanggal' => $item->created_at->format('Y-m-d'),
                'keterangan' => 'Pembayaran (TF)',
            ]);
        }

        return view('pengeluaran.makan', compact(
            'orang',
            'daftarNama',
            'nama',
            'pengeluaranHariIni',
            'cashHariIni',
            'tfHariIni',
            'totalHarian',
            'totalCash',
            'totalTf',
            'cashflow',
            'rekapPemasanganHariIni'
        ));
    }


    public function simpan(Request $request)
    {
        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';
        $namaList = $request->input('nama', []); // array dari checkbox
        $volume = count($namaList);

        $kategori = $request->input('kategori');
        $harga_satuan = $request->input('harga_satuan');
        $harga_total = $harga_satuan * $volume;
        // Format deskripsi berdasarkan kategori

        $deskripsi = ucfirst($kategori) . ' : ' . implode(', ', $namaList);

        PengeluaranModel::create([
            'keterangan' => $kategori . ', (dicatat : ' . (Auth::user()->name ?? 'Unknown Admin') . ')',
            'deskripsi' => $deskripsi,
            'harga_satuan' => $harga_satuan,
            'volume' => $volume,
            'harga_total' => $harga_total,
            'kategori' => $kategori,
        ]);
        return redirect()->route('pengeluaran.makan')->with('success', 'Pengeluaran berhasil dicatat.');
    }
}

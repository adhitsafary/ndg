<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;
use App\Models\RekapPemasanganModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JumlahLainLainController extends Controller
{
    public function tambahPemasukan(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'harga_total' => 'required|numeric|min:0',
        ]);

        // Menyimpan data pemasukan ke dalam tabel
        $pemasukan = new PemasukanModel();
        $pemasukan->jumlah = $validated['harga_total'];
        $pemasukan->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    // Fungsi untuk menambahkan pengeluaran
    public function tambahPengeluaran(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'harga_total' => 'required|numeric|min:0',
        ]);

        // Menyimpan data pengeluaran ke dalam tabel
        $pengeluaran = new PengeluaranModel();
        $pengeluaran->jumlah = $validated['harga_total'];
        $pengeluaran->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    // Fungsi untuk menambahkan pengeluaran
    public function RekapPemasangan(Request $request)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ]);

        // Menyimpan data pemasukan ke dalam tabel
        $pemasukan = new RekapPemasanganModel();
        $pemasukan->jumlah = $validated['registrasi'];
        $pemasukan->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Pemasukan berhasil ditambahkan.');
    }





    // Fungsi untuk mendapatkan total pemasukan, pengeluaran, dan pembayaran harian
    public function lihatRekapHarian(Request $request)
    {
        // Cek apakah ada input tanggal, jika tidak gunakan tanggal hari ini
        $tanggalHariIni = $request->input('tanggal') ?? Carbon::now()->format('Y-m-d');

        // Mengambil total pemasukan dan pengeluaran berdasarkan tanggal yang dipilih
        $totalPemasukan = PemasukanModel::whereDate('created_at', $tanggalHariIni)->sum('harga_total');
        $totalPengeluaran = PengeluaranModel::whereDate('created_at', $tanggalHariIni)->sum('harga_total');
        $totalRegistrasi = RekapPemasanganModel::whereDate('created_at', $tanggalHariIni)->sum('registrasi');

        $pembayaranHarian = BayarPelanggan::whereDate('created_at', $tanggalHariIni)
            ->get();

        $totalPendapatanHarian = $pembayaranHarian->sum('jumlah_pembayaran') + $totalPemasukan;
        $paket_plg = $pembayaranHarian->sum('peket_plg');

        $pemasukantotal = $totalPemasukan - $totalPengeluaran;
        $totalsaldo = $totalPendapatanHarian - $totalPengeluaran;
        $totaljumlahsaldo = $totalRegistrasi + $totalsaldo;

        $totalUserHarian = $pembayaranHarian->count();

        // Ambil data by metode_transaksi
        $cash = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->where('metode_transaksi', 'CASH');
        $tf = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->where('metode_transaksi', 'TF');

        // Breakdown data cash dan tf
        $cash = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->where('metode_transaksi', 'CASH');
        $tf   = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->where('metode_transaksi', 'TF');

        $cashTagihan = (clone $cash)->where('untuk_pembayaran', 'Tagihan')->sum('jumlah_pembayaran');
        $cashPiutang = (clone $cash)->where('untuk_pembayaran', 'piutang')->sum('jumlah_pembayaran');
        $cashPsb     = (clone $cash)->where('untuk_pembayaran', 'PSB')->sum('jumlah_pembayaran');

        $tfTagihan = (clone $tf)->where('untuk_pembayaran', 'Tagihan')->sum('jumlah_pembayaran');
        $tfPiutang = (clone $tf)->where('untuk_pembayaran', 'piutang')->sum('jumlah_pembayaran');
        $tfPsb     = (clone $tf)->where('untuk_pembayaran', 'PSB')->sum('jumlah_pembayaran');


        // List pengeluaran detail
        $listPengeluaran = PemasukanModel::whereDate('created_at', $tanggalHariIni)->get();
        $listPemasukan = PengeluaranModel::whereDate('created_at', $tanggalHariIni)->get();

        return view('rekap_harian.index', compact(
            'tanggalHariIni',
            'totalPendapatanHarian',
            'totalPemasukan',
            'totalPengeluaran',
            'totalRegistrasi',
            'totaljumlahsaldo',
            'paket_plg',
            'pembayaranHarian',
            'totalUserHarian',
            'cashTagihan',
            'cashPiutang',
            'cashPsb',
            'tfTagihan',
            'tfPiutang',
            'tfPsb',
            'listPengeluaran',
            'totalsaldo',
            'listPemasukan',
        ));
    }


    public function pembayaran(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $metode = $request->input('metode');

        $query = BayarPelanggan::query();

        if ($tanggal) {
            $query->whereDate('created_at', $tanggal);
        }

        if ($metode) {
            $query->where('metode_transaksi', $metode);
        }

        $data = $query->get();

        return view('history.pembayaran', compact('data', 'tanggal', 'metode'));
    }
}

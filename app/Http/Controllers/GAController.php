<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Inventory;
use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use App\Models\User;
use App\Models\X100c;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $hariIni = Carbon::now()->toDateString(); // Ambil tanggal hari ini dalam format YYYY-MM-DD
        // Ambil semua user
        $user = User::whereDate('created_at', $hariIni)->get();

        // Filter data absensi berdasarkan tanggal hari ini
        $absensi = X100c::whereDate('created_at', $hariIni)->get();

        // Filter pembayaran berdasarkan tanggal hari ini
        $pembayaran = BayarPelanggan::whereDate('created_at', $hariIni)->get();

        // Filter perbaikan yang berstatus 'Success' berdasarkan tanggal hari ini
        $perbaikan = Perbaikan::where('status', 'Success')
            ->whereDate('created_at', $hariIni)
            ->get();

        // Filter pemasangan (PSB) yang berstatus 'open' berdasarkan tanggal hari ini
        $psb = RekapPemasanganModel::where('status', 'open')
            ->whereDate('created_at', $hariIni)
            ->get();

        $pelanggan = Pelanggan::whereDate('tgl_tagih_plg', $hariIni)
            ->orderBy('tgl_tagih_plg', 'asc')
            ->get();

        //AMBIL TANGGAL TAGIH * JUMLAH PEMBAYARAN USER
        $totalPendapatanharian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())
            ->sum('jumlah_pembayaran'); // Pastikan 'jumlah_pembayaran' adalah kolom yang menyimpan jumlah pembayaran
        $todayDay = Carbon::today()->day;
        // Menghitung total jumlah pengguna yang membayar hari ini dari semua metode transaksi
        $totalUserHarian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())->count();
        // Ambil semua pelanggan yang memiliki tgl_tagih_plg sama dengan hari ini (angka)
        $pembayaranHariiniPelanggan = Pelanggan::where('tgl_tagih_plg', $todayDay)->get();
        // Hitung total tagihan dari pelanggan yang harus membayar hari ini
        $totalTagihanHariIni = $pembayaranHariiniPelanggan->sum('harga_paket');
        // Hitung jumlah pelanggan yang membayar hari ini
        $jumlahPelangganMembayarHariIni = $pembayaranHariiniPelanggan->count();
        $total_jml_user = BayarPelanggan::whereDate('created_at', $hariIni)->count();
        //total jumlah yang tertagih harian
        $totalTagihanTertagih = $totalTagihanHariIni - $totalPendapatanharian_semua;
        //total user yang tertagih harian
        $totalUserTertagih = $jumlahPelangganMembayarHariIni - $totalUserHarian_semua;
        $inventories = Inventory::all();

        $perbaikanProses = Perbaikan::where('status', 'Proses', )->where('kategori', 'ndg')->get();
        $total_perbaikan = $perbaikanProses->count();
        $perbaikakn_tampil = $perbaikanProses->take(5);


        //ini WO
        $perbaikanWO = Perbaikan::where('kategori', 'wo')->get();
        $total_WO = $perbaikanProses->count();
        $Wo_tampil = $perbaikanProses->take(10);


        $PSBProses = RekapPemasanganModel::where('status', 'Proses')->get();
        $totalPSB = $PSBProses->count();
        $PSB_tampil = $PSBProses->take(5);



        return view('GA.index', compact(
            'total_WO',
            'Wo_tampil',
            'perbaikanWO',
            'PSBProses',
            'totalPSB',
            'PSB_tampil',
            'perbaikanProses',
            'total_perbaikan',
            'perbaikakn_tampil',
            'inventories',
            'user',
            'absensi',
            'pembayaran',
            'perbaikan',
            'psb',
            'pelanggan',
            //data pelanggan
            'pembayaranHariiniPelanggan',
            'jumlahPelangganMembayarHariIni',
            'totalTagihanHariIni',
            //Total Tertagih
            'totalTagihanTertagih',
            'totalUserTertagih',
            'totalPendapatanharian_semua',
            'totalUserHarian_semua',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BayarPelanggan;
use App\Models\Inventory;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use App\Models\PemasukanModel;
use App\Models\Pemberitahuan;
use App\Models\PengeluaranModel;
use App\Models\Perbaikan;
use App\Models\Pesan;
use App\Models\RekapPemasanganModel;
use App\Models\Target;
use App\Models\User;
use App\Models\X100c;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TVController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index2()
    {
        $query_pembayaran = Pelanggan::where('status_pembayaran', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        $total_pembayaran_bulanan = $query_pembayaran->count();

        return view('tv.index', compact(
            'query_pembayaran',
            'total_pembayaran_bulanan',
        ));
    }


    public function index(Request $request)
    {
        // Ambil data pelanggan dan pelanggan off
        $pelanggan = Pelanggan::all();
        $pelangganof = Pelangganof::all();


        $perbaikan = Perbaikan::all();

        $perbaikanProses = Perbaikan::where('status', 'Proses')->get();
        $total_perbaikan = $perbaikanProses->count();
        $perbaikan_limited = $perbaikanProses->take(5);

        // $rekap_pemasangan = RekapPemasanganModel::whereMonth('tgl_aktivasi', Carbon::now()->month)
        //    ->whereYear('tgl_aktivasi', Carbon::now()->year)
        //    ->orderBy('tgl_aktivasi', 'desc')
        //    ->get();


        //Pengeluaran
        $pengeluaran = PengeluaranModel::whereDay('created_at', Carbon::now()->day)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_pengeluaran = $pengeluaran->sum('jumlah');
        $rekap_pengeluaran_limited = $pengeluaran->take(5);


        $pemasukan = PemasukanModel::whereDay('created_at', Carbon::now()->day)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_pemasukan = $pemasukan->sum('jumlah');
        $rekap_pemasukan_limited = $pemasukan->take(5);


        $kehadiran = X100c::where('status', 'masuk')->whereDay('created_at', Carbon::now()->day)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_kehadiran = $kehadiran->count();
        $rekap_kehadiran_limited = $kehadiran->take(5);



        // Hitung total pendapatan bulanan
        $totalPendapatanBulanan = $pelanggan->sum('harga_paket');

        // Hitung total jumlah pengguna
        $totalJumlahPengguna = $pelanggan->count();

        // Hitung total pengurangan pendapatan dari pelanggan off bulanan
        $pelangganofuang = $pelangganof->sum('harga_paket');

        // Hitung total jumlah pengguna pelanggan off
        $pelangganoforang = $pelangganof->count();

        $pelanggan_of = $pelangganof->count();
        $pelanggan_of_uang = $pelangganof->sum('harga_paket');

        $totalpendapatanakhir = $totalPendapatanBulanan + $pelangganofuang;
        $totaluser = $totalJumlahPengguna + $pelangganoforang;

        // Penjualan harga paket
        $paketData = DB::table('pelanggan')
            ->select('harga_paket', DB::raw('count(*) as total_user'))
            ->groupBy('harga_paket')
            ->orderBy('total_user', 'desc')
            ->get();


        // Membagi data menjadi dua: 5 teratas dan sisanya
        $paketTop5 = $paketData->take(3); // Mengambil 5 teratas
        $paketRemaining = $paketData->skip(3);

        // Perbaikan dashboard
        $perbaikans = Perbaikan::all();


        // Pendapatan bulanan
        $pendapatanBulanan = BayarPelanggan::selectRaw('MONTH(tanggal_pembayaran) as bulan, SUM(jumlah_pembayaran) as total_pendapatan')
            ->whereYear('tanggal_pembayaran', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();



        // Format data untuk dikirim ke view
        $dataPendapatan = array_fill(0, 12, 0); // Isi awal dengan 0 untuk 12 bulan
        foreach ($pendapatanBulanan as $pendapatan) {
            if ($pendapatan->bulan >= 9 && $pendapatan->bulan <= 12) {
                $dataPendapatan[$pendapatan->bulan - 1] = $pendapatan->total_pendapatan; // Sesuaikan indeks bulan
            }
        }

        // Ambil data chart untuk laporan
        $pembayaranPerBulan = BayarPelanggan::selectRaw('MONTH(tanggal_pembayaran) as bulan, SUM(jumlah_pembayaran) as total')
            ->whereYear('tanggal_pembayaran', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Siapkan array untuk data chart
        $dataChart = [
            'labels' => [],
            'data' => []
        ];

        // Pastikan data dimulai dari September
        $startMonth = 9; // September
        $endMonth = 12; // Desember

        foreach ($pembayaranPerBulan as $pembayaran) {
            $bulan = $pembayaran->bulan;
            if ($bulan >= $startMonth && $bulan <= $endMonth) {
                $dataChart['labels'][] = $this->getBulan($bulan);
                $dataChart['data'][] = $pembayaran->total;
            }
        }

        // Isi bulan-bulan sebelum September dengan 0
        for ($i = 1; $i < $startMonth; $i++) {
            $dataChart['labels'][] = $this->getBulan($i);
            $dataChart['data'][] = 0;
        }

        // Ambil total jumlah_pembayaran dari tabel berdasarkan bulan September (atau bulan lain dari tanggal saat ini)
        $bulanSekarang = now()->format('m'); // Mendapatkan bulan dari sistem (sekarang)
        $dataPendapatanbulan = DB::table('bayar_pelanggan')
            ->whereMonth('tanggal_pembayaran', $bulanSekarang)
            ->sum('jumlah_pembayaran'); // Menjumlahkan total pembayaran di bulan yang sama


        $tanggalMulai = $request->input('tanggal_mulai', now()->format('Y-m-d')); // Default ke hari ini
        $tanggalAkhir = $request->input('tanggal_akhir', now()->format('Y-m-d')); // Default ke hari ini
        // Ambil data pembayaran yang dilakukan antara tanggal mulai dan akhir (default hari ini)
        $pembayaranHarian = BayarPelanggan::whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalAkhir])->get();
        // Hitung total pendapatan harian
        $totalPendapatanharian = $pembayaranHarian->sum('jumlah_pembayaran');
        // Hitung total pendapatan harian
        $totaluserhasilfilter = $pembayaranHarian->count();

        //INI BARU TOTAL HARIAN
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        // Mengambil total pemasukan dan pengeluaran untuk hari ini
        $totalPemasukan = PemasukanModel::whereDate('created_at', $tanggalHariIni)->sum('harga_total');
        $totalPengeluaran = PengeluaranModel::whereDate('created_at', $tanggalHariIni)->sum('harga_total');
        $total_user_bayar = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->sum('jumlah_pembayaran');
        $totalRegistrasi = RekapPemasanganModel::whereDate('created_at', $tanggalHariIni)->sum('registrasi');
        //baru
        $total_cash =

            $pembayaranHarian = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())
            ->where('metode_transaksi', '!=', 'TF') // Kecualikan metode transaksi 'TF'
            ->get();

        $pembayaranHarian_created_at = BayarPelanggan::whereDate('created_at', Carbon::today())
            ->where('metode_transaksi', '!=', 'TF') // Kecualikan metode transaksi 'TF'
            ->get();


        // $totalUserHarian = $pembayaranHarian->count(); ini harian tanggal
        $totalUserHarian = $pembayaranHarian_created_at->count();
        $totalPendapatanHarian = $pembayaranHarian_created_at->sum('jumlah_pembayaran');
        $pemasukantotal = $totalPemasukan - $totalPengeluaran;
        $totaljumlahsaldo = $totalPendapatanHarian + $pemasukantotal + $totalRegistrasi;
        //$totaljumlahsaldo = $totalRegistrasi + $totalsaldo;

        // Menghitung total jumlah pengguna yang membayar hari ini dari semua metode transaksi
        $totalUserHarian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())->count();

        // Hitung total pendapatan harian dari pembayaran
        $totalPendapatanharian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())
            ->sum('jumlah_pembayaran'); // Pastikan 'jumlah_pembayaran' adalah kolom yang menyimpan jumlah pembayaran

        //AMBIL TANGGAL TAGIH * JUMLAH PEMBAYARAN USER
        $todayDay = Carbon::today()->day;
        // Ambil semua pelanggan yang memiliki tgl_tagih_plg sama dengan hari ini (angka)
        $pembayaranHariiniPelanggan = Pelanggan::where('tgl_tagih_plg', $todayDay)->get();

        // Hitung total tagihan dari pelanggan yang harus membayar hari ini
        $totalTagihanHariIni = $pembayaranHariiniPelanggan->sum('harga_paket');

        // Hitung jumlah pelanggan yang membayar hari ini
        $jumlahPelangganMembayarHariIni = $pembayaranHariiniPelanggan->count();
        $total_jml_user = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->count();


        //total jumlah yang tertagih harian
        $totalTagihanTertagih = $totalTagihanHariIni - $totalPendapatanharian_semua;
        //total user yang tertagih harian
        $totalUserTertagih = $jumlahPelangganMembayarHariIni - $totalUserHarian_semua;

        $sisaHarian = $totalTagihanHariIni - $totalTagihanTertagih;
        $sisaPelangganHarian = $jumlahPelangganMembayarHariIni - $totalUserTertagih;




        $target = Target::where('nama_target', 'marketing')->first(['jumlah_target', 'sisa_target', 'hari_tersisa']);


        $jumlah_target = $target->jumlah_target;
        $sisa_target = $target->sisa_target;
        $hari_tersisa = $target->hari_tersisa;
        $hasil_target = $jumlah_target - $sisa_target;


        $currentDate = Carbon::today(); // Fokus hanya hari ini

        $pembayaranData = DB::table('bayar_pelanggan')
            ->select(
                DB::raw('HOUR(tanggal_pembayaran) as jam'),
                DB::raw('SUM(jumlah_pembayaran) as total_pembayaran')
            )
            ->whereDate('tanggal_pembayaran', $currentDate)
            ->groupBy(DB::raw('HOUR(tanggal_pembayaran)'))
            ->orderBy(DB::raw('HOUR(tanggal_pembayaran)'), 'asc')
            ->get();

        $labels = [];
        $totalPembayaran = [];

        for ($i = 0; $i < 24; $i++) {
            $dataJam = $pembayaranData->firstWhere('jam', $i);

            $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ":00"; // Misal 01:00, 14:00
            $totalPembayaran[] = $dataJam ? $dataJam->total_pembayaran : 0;
        }


        //INI WO WORK ORDER
        $perbaikanWO = Perbaikan::where('status', 'Proses')->where('kategori', 'wo')->get();
        $total_WO = $perbaikanWO->count();
        $Wo_tampil = $perbaikanWO->take(5);

        //INI Perbaikan yang proses saja
        $perbaikanProses = Perbaikan::where('status', 'Proses')->where('kategori', 'ndg')->get();
        $total_perbaikan = $perbaikanProses->count();
        $perbaikan_limited = $perbaikanProses->take(5);

        //Inventory
        $inventories = Inventory::all();


        //$total_pemasanganBulanan = RekapPemasanganModel::whereMonth('tgl_aktivasi', Carbon::now()->month)
        //     ->whereYear('tgl_aktivasi', Carbon::now()->year)
        //     ->orderBy('tgl_aktivasi', 'desc')
        //        ->get();


        $rekap_pemasangan = RekapPemasanganModel::where('status', 'Proses')->get();
        $total_pemasangan = $rekap_pemasangan->count();

        $rekap_pemasangan_limited = $rekap_pemasangan->take(4);



        //CHART PSB
        $pemasanganData = DB::table('rekap_pemasangan')
            ->select(
                DB::raw('MONTH(tgl_aktivasi) as bulan'),
                DB::raw('COUNT(id) as total_pemasangan'),
                DB::raw('SUM(registrasi) as total_pendapatan')
            )
            ->whereYear('tgl_aktivasi', date('Y')) // Filter hanya data tahun ini
            ->groupBy(DB::raw('MONTH(tgl_aktivasi)')) // Kelompokkan berdasarkan bulan
            ->orderBy(DB::raw('MONTH(tgl_aktivasi)'), 'asc') // Urutkan berdasarkan bulan
            ->get();

        // Persiapan data untuk chart
        $labels = [];
        $totalPemasangan = [];
        $totalPendapatan = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataBulan = $pemasanganData->firstWhere('bulan', $i);

            $labels[] = Carbon::createFromDate(null, $i, 1)->format('F'); // Nama bulan
            $totalPemasangan[] = $dataBulan ? $dataBulan->total_pemasangan : 0;
            $totalPendapatan[] = $dataBulan ? $dataBulan->total_pendapatan : 0;
        }


        $pemberitahuan = Pemberitahuan::all();

        // $queryfull = Pelanggan::query();
        $query = Pelanggan::whereIn('status_pembayaran', ['paid', 'unpaid', 'isolir'])->get();
        $querySudahBayar = Pelanggan::where('status_pembayaran', 'paid')->get();
        $queryBelumBayar = Pelanggan::whereIn('status_pembayaran', ['unpaid', 'isolir'])->get();

        $total_jml_pembayaran = $query->sum('harga_paket');
        $total_plg_pembayaran = $query->count();

        $total_bayar = $querySudahBayar->sum('harga_paket');
        $total_user_bayar = $querySudahBayar->count();

        $belum_bayar = $queryBelumBayar->sum('harga_paket');
        $total_user_belum = $queryBelumBayar->count();


        //Ini Pembayaran samping
        $pembayaran_CASH = BayarPelanggan::where('metode_transaksi', 'CASH')->whereDate('created_at', Carbon::today())->get();
        $pembayaran_TF = BayarPelanggan::where('metode_transaksi', 'TF')->whereDate('created_at', Carbon::today())->get();
        $pembaran_al = BayarPelanggan::whereDate('created_at', Carbon::today())->get();

        $total_cash = $pembayaran_CASH->sum('jumlah_pembayaran');
        $total_user_cash = $pembayaran_CASH->count();

        $total_TF = $pembayaran_TF->sum('jumlah_pembayaran');
        $total_user_tf = $pembayaran_TF->count();

        $total_payment = $pembaran_al->sum('jumlah_pembayaran');
        $total_user_payment = $pembaran_al->count();


        $query_pengeluaran_harian = PengeluaranModel::whereDate('created_at', Carbon::today())->get();
        $query_pemasukan_harian = PemasukanModel::whereDate('created_at', Carbon::today())->get();

        $pengeluaran_harian = $query_pengeluaran_harian->sum('harga_total');
        $pemasukan_harian = $query_pemasukan_harian->sum('harga_total');
        $total_harian = $pemasukan_harian - $pengeluaran_harian;


        //PIUTANG dan Tagihan

        $query_tagihan = BayarPelanggan::where('untuk_pembayaran', 'tagihan')->whereDate('created_at', Carbon::today())->get();
        $query_piutang = BayarPelanggan::where('untuk_pembayaran', 'piutang')->whereDate('created_at', Carbon::today())->get();
        $query_tagihan_piutang = BayarPelanggan::whereDate('created_at', Carbon::today())->get();

        $uang_tagihan = $query_tagihan->sum('jumlah_pembayaran');
        $user_tagihan = $query_tagihan->count();

        $uang_piutang = $query_piutang->sum('jumlah_pembayaran');
        $user_piutang = $query_piutang->count();

        $total_tagihan_piutang  = $query_tagihan_piutang->sum('jumlah_pembayaran');
        $total_user_tagihan_piutang = $query_tagihan_piutang->count();


        //Pemsangan
        $query_pemasangan = RekapPemasanganModel::whereIn('status', ['selesai', 'open'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->get();
        $pemasangan_bulanan = $query_pemasangan->count();

        //Perbaikan
        $query_perbaikan = Perbaikan::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
        $total_perbaikan = $query_perbaikan->count();

        //Modem
        $modem = Inventory::where('kategori', 'Modem')->get();

        $total_modem = $modem->count();

        $users = User::select('name', 'email', 'last_login_at')->get();

        $tanggalHariIni = Carbon::now()->day; // Ambil hanya angka tanggal (1-31)

        // Ambil semua pelanggan dengan status pembayaran tertentu sesuai tanggal tagihan hari ini
        $query_harian = Pelanggan::whereIn('status_pembayaran', ['paid', 'unpaid', 'isolir'])
            ->where('tgl_tagih_plg', $tanggalHariIni)
            ->get();

        $querySudahBayar_harian = Pelanggan::where('status_pembayaran', 'paid')
            ->where('tgl_tagih_plg', $tanggalHariIni)
            ->get();

        $queryBelumBayar_harian = Pelanggan::whereIn('status_pembayaran', ['unpaid', 'isolir'])
            ->where('tgl_tagih_plg', $tanggalHariIni)
            ->get();

        // Total pembayaran hari ini (semua pelanggan)
        $total_jml_pembayaran_harian = $query_harian->sum('harga_paket');
        $total_plg_pembayaran_harian = $query_harian->count();

        // Total pelanggan yang sudah bayar hari ini
        $total_bayar_harian = $querySudahBayar_harian->sum('harga_paket');
        $total_user_bayar_harian = $querySudahBayar_harian->count();


        $belum_sisa_bayar_harian = $queryBelumBayar_harian->sum('harga_paket');
        $total_user_sisa_harian = $queryBelumBayar_harian->count();

        $pesan = Pesan::all();

        $logs = ActivityLog::with('user')->latest()->paginate(20);


        $query_pelanggans = Pelanggan::where('status_pembayaran', 'paid')
            ->whereDate('updated_at', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();



        return view('tv.index', compact(
            'query_pelanggans',
            'logs',
            'pesan',
            'total_jml_pembayaran_harian',
            'total_plg_pembayaran_harian',
            'total_bayar_harian',
            'total_user_bayar_harian',
            'belum_sisa_bayar_harian',
            'total_user_sisa_harian',
            'sisaHarian',
            'sisaPelangganHarian',
            'users',
            'total_modem',
            'total_perbaikan',
            'pemasangan_bulanan',
            'uang_tagihan',
            'user_piutang',
            'uang_piutang',
            'user_tagihan',
            'total_tagihan_piutang',
            'total_user_tagihan_piutang',
            'pengeluaran_harian',
            'pemasukan_harian',
            'total_harian',
            'total_cash',
            'total_user_cash',
            'total_TF',
            'total_user_tf',
            'total_payment',
            'total_user_payment',
            'total_plg_pembayaran',
            'total_jml_pembayaran',
            'total_bayar',
            'total_user_bayar',
            'belum_bayar',
            'total_user_belum',
            'totalPendapatan',
            'totalPemasangan',
            'total_WO',
            'inventories',
            'Wo_tampil',
            'perbaikanWO',
            'pelanggan',
            'tanggalMulai',
            'tanggalAkhir',
            'dataPendapatanbulan',
            'totalJumlahPengguna', // Hanya dikirimkan sekali
            'dataPendapatan',
            'totalUserHarian',
            'totalPendapatanharian',
            'perbaikans',
            'paketTop5',
            'paketRemaining',
            'paketData',
            'pelanggan_of',
            'pelanggan_of_uang',
            'totalpendapatanakhir',
            'totaluser',
            'pelangganofuang',
            'pelangganoforang',
            'totalPendapatanBulanan',
            'dataChart',
            //data baru
            'totalRegistrasi',
            //'totalsaldo',
            'totaljumlahsaldo',
            'totalPemasukan',
            'totalPengeluaran',
            'tanggalHariIni',
            'totalUserHarian_semua',
            'totalPendapatanharian_semua',
            'totaluserhasilfilter',

            //data pelanggan
            'pembayaranHariiniPelanggan',
            'jumlahPelangganMembayarHariIni',
            'totalTagihanHariIni',
            //Total Tertagih
            'totalTagihanTertagih',
            'totalUserTertagih',
            //chart baru
            'labels',
            'totalPembayaran',
            //pembayaran hari ini total
            'total_user_bayar',
            'total_jml_user',
            //filter lingkaran baru
            'sisa_target',
            'jumlah_target',
            'hari_tersisa',
            'hasil_target',
            //runing text
            'perbaikanProses',
            'pemberitahuan',
            'rekap_pemasangan',
            'total_pemasangan',
            'rekap_pemasangan_limited',
            'perbaikan',
            'total_perbaikan',
            'perbaikan_limited',
            'pengeluaran',
            'total_pengeluaran',
            'rekap_pengeluaran_limited',
            'pemasukan',
            'total_pemasukan',
            'rekap_pemasukan_limited',
            'kehadiran',
            'total_kehadiran',
            'rekap_kehadiran_limited',





        ));
    }


    private function getBulan($bulan)
    {
        $bulanArray = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];
        return $bulanArray[$bulan];
    }



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

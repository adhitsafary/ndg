<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\IsolirModel;
use App\Models\NetDigitalGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use App\Models\PemasukanModel;
use App\Models\PembayaranPelanggan;
use App\Models\Pemberitahuan;
use App\Models\PengeluaranModel;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use App\Models\Target;
use App\Models\X100c;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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



    public function home(Request $request)
    {
        // Ambil data pelanggan dan pelanggan off
        $pelanggan = Pelanggan::all();
        $pelangganof = Pelangganof::all();
        $perbaikanProses = Perbaikan::where('status', 'Proses')->get();

        $pemberitahuan = Pemberitahuan::all();
        $perbaikan = Perbaikan::all();

        $total_perbaikan = $perbaikanProses->count();
        $perbaikan_limited = $perbaikanProses->take(5);

        $rekap_pemasangan = RekapPemasanganModel::whereMonth('tgl_aktivasi', Carbon::now()->month)
            ->whereYear('tgl_aktivasi', Carbon::now()->year)
            ->orderBy('tgl_aktivasi', 'desc')
            ->get();
        $total_pemasangan = $rekap_pemasangan->count();
        $rekap_pemasangan_limited = $rekap_pemasangan->take(5);

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


        $kehadiran = X100c::whereDay('created_at', Carbon::now()->day)
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

        // Menghitung total user di semua paket
        //totalUsers = $paketData->sum('total_user');

        // Membagi data menjadi dua: 5 teratas dan sisanya
        $paketTop5 = $paketData->take(3); // Mengambil 5 teratas
        $paketRemaining = $paketData->skip(3);

        // Perbaikan dashboard
        $perbaikans = Perbaikan::all();

        // Pembayaran harian


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




        $target = Target::where('nama_target', 'marketing')->first(['jumlah_target', 'sisa_target', 'hari_tersisa']);

        $jumlah_target = $target->jumlah_target;
        $sisa_target = $target->sisa_target;
        $hari_tersisa = $target->hari_tersisa;
        $hasil_target = $jumlah_target - $sisa_target;


        $currentMonth = Carbon::now()->month; // Bulan saat ini
        $currentYear = Carbon::now()->year; // Tahun saat ini

        $pembayaranData = DB::table('bayar_pelanggan')
            ->select(
                DB::raw('MONTH(tanggal_pembayaran) as bulan'),
                DB::raw('COUNT(id) as total_user'),
                DB::raw('SUM(jumlah_pembayaran) as total_pembayaran')
            )
            ->whereYear('tanggal_pembayaran', $currentYear)  // Filter berdasarkan tahun saat ini
            ->groupBy(DB::raw('MONTH(tanggal_pembayaran)')) // Group berdasarkan bulan
            ->orderBy(DB::raw('MONTH(tanggal_pembayaran)'), 'asc') // Urutkan berdasarkan bulan
            ->get();

        $labels = [];
        $totalUsers = [];
        $totalPembayaran = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataBulan = $pembayaranData->firstWhere('bulan', $i);

            // Menambahkan data untuk setiap bulan
            $labels[] = Carbon::createFromDate($currentYear, $i, 1)->format('F'); // Nama bulan
            $totalUsers[] = $dataBulan ? $dataBulan->total_user : 0; // Total user untuk bulan ini
            $totalPembayaran[] = $dataBulan ? $dataBulan->total_pembayaran : 0; // Total pembayaran untuk bulan ini
        }



        // Kirim data ke view
        return view('finance.index', compact(
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
            'totalUsers',
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
}

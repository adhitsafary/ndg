<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use App\Models\PemasukanModel;
use App\Models\Pemberitahuan;
use App\Models\PengeluaranModel;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use App\Models\Target;
use App\Models\X100c;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function redirectToPelanggan()
    {
        // Mendapatkan tanggal saat ini
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter tgl_tagih_plg
        return redirect()->to('pelanggan?tgl_tagih_plg=' . $tanggalHariIni);
    }

    public function showPelangganBelumBayar()
    {
        // Mendapatkan tanggal hari ini (opsional)
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter status_pembayaran
        return redirect()->to('pelanggan?tgl_tagih_plg=' . $tanggalHariIni . '&paket_plg=&harga_paket=&status_pembayaran=unpaid');
    }

    public function showPelangganSudahBayar()
    {
        // Mendapatkan tanggal hari ini (opsional)
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter status_pembayaran
        return redirect()->to('pelanggan?tgl_tagih_plg=' . $tanggalHariIni . '&paket_plg=&harga_paket=&status_pembayaran=paid');
    }

    public function historyhariini()
    {
        // Mendapatkan tanggal hari ini (opsional)
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter status_pembayaran
        return redirect()->to('pembayaran/filter?created_at='  . $tanggalHariIni);
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
        $totalPemasukan = PemasukanModel::whereDate('created_at', $tanggalHariIni)->sum('jumlah');
        $totalPengeluaran = PengeluaranModel::whereDate('created_at', $tanggalHariIni)->sum('jumlah');
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
        return view('home.index', compact(
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

    public function pemasangan(Request $request)
    {
        $rekap_pemasangan = RekapPemasanganModel::whereMonth('tgl_aktivasi', Carbon::now()->month)
            ->whereYear('tgl_aktivasi', Carbon::now()->year)
            ->orderBy('tgl_aktivasi', 'desc')
            ->get();

        $total_pemasangan = $rekap_pemasangan->count();

        // Batasi jumlah data yang ditampilkan, misalnya 10
        $rekap_pemasangan_limited = $rekap_pemasangan->take(10);

        // Kirim data ke view
        return view('home.pemasangan', compact(
            'rekap_pemasangan',
            'total_pemasangan',
            'rekap_pemasangan_limited'
        ));
    }


    public function perbaikan(Request $request)
    {
        $perbaikanProses = Perbaikan::where('status', 'Proses')->get();
        $total_perbaikan = $perbaikanProses->count();
        $perbaikan_limited = $perbaikanProses->take(10);

        // Kirim data ke view
        return view('home.perbaikan', compact(
            'perbaikanProses',
            'total_perbaikan',
            'perbaikan_limited'
        ));
    }

    public function pemberitahuan(Request $request)
    {
        $pemberitahuan = Pemberitahuan::all();

        // Kirim data ke view
        return view('home.pemberitahuan', compact(
            'pemberitahuan',

        ));
    }

    public function jam(Request $request)
    {

        return view('home.jam');
    }

    public function pemasukan(Request $request)
    {

        return view('home.pemasukan');
    }


    public function isolir()
    {
        // Mengambil data berdasarkan tanggal tagihan dari tabel 'pelanggan', menghitung jumlah total pelanggan dan total pembayaran per tanggal tagihan
        $pelanggan = Pelanggan::select(
            'tgl_tagih_plg',
            DB::raw('harga_paket'),
            DB::raw('COUNT(id_plg) as jumlah_pelanggan'),
            DB::raw('COUNT(id_plg) * harga_paket as total_pembayaran')
        )
            ->groupBy('tgl_tagih_plg', 'harga_paket')  // Mengelompokkan berdasarkan tanggal tagihan dan harga paket
            ->orderBy('tgl_tagih_plg', 'asc')  // Urutkan berdasarkan tanggal tagihan
            ->get();

        // Menggabungkan data berdasarkan tanggal tagih_plg
        $finalResult = $pelanggan->groupBy('tgl_tagih_plg')->map(function ($items) {
            // Totalkan semua pelanggan dan total pembayaran per tanggal
            $jumlahPelanggan = $items->sum('jumlah_pelanggan');
            $totalPembayaran = $items->sum('total_pembayaran');

            return [
                'jumlah_pelanggan' => $jumlahPelanggan,
                'total_pembayaran' => $totalPembayaran,
                'tgl_tagih_plg' => $items->first()->tgl_tagih_plg  // Pastikan kita mendapatkan tgl_tagih_plg yang benar
            ];
        });

        // Mengambil data pembayaran per bulan dan tahun berdasarkan created_at
        $pembayaran = BayarPelanggan::select(
            'tgl_tagih_plg',
            DB::raw('SUM(jumlah_pembayaran) as total_pembayaran_diterima')
        )
            ->whereMonth('created_at', date('m'))  // Mengambil data hanya untuk bulan sekarang berdasarkan created_at
            ->whereYear('created_at', date('Y'))  // Mengambil data hanya untuk tahun sekarang berdasarkan created_at
            ->groupBy('tgl_tagih_plg')  // Mengelompokkan berdasarkan tanggal tagihan
            ->get();

        // Gabungkan hasil dari dua query berdasarkan tanggal tagihan
        $mergedResults = $finalResult->map(function ($pelangganData) use ($pembayaran) {
            $tanggalTagihan = $pelangganData['tgl_tagih_plg'];
            $bayarData = $pembayaran->firstWhere('tgl_tagih_plg', $tanggalTagihan);

            // Menghitung selisih
            $totalPembayaranDiterima = $bayarData ? $bayarData->total_pembayaran_diterima : 0;
            $selisihPembayaran = $pelangganData['total_pembayaran'] - $totalPembayaranDiterima;

            return [
                'tgl_tagih_plg' => $tanggalTagihan,
                'jumlah_pelanggan' => $pelangganData['jumlah_pelanggan'],
                'total_pembayaran' => $pelangganData['total_pembayaran'],
                'total_pembayaran_diterima' => $totalPembayaranDiterima,
                'selisih_pembayaran' => $selisihPembayaran
            ];
        });

        // Menambahkan row untuk total seluruh pembayaran di akhir
        $totalPembayaran = $mergedResults->sum('total_pembayaran');
        $totalPembayaranDiterima = $mergedResults->sum('total_pembayaran_diterima');
        $totalJumlahPelanggan = $mergedResults->sum('jumlah_pelanggan');
        $totalSelisihPembayaran = $totalPembayaran - $totalPembayaranDiterima;

        // Menambahkan baris total ke dalam hasil
        $mergedResults->push([
            'tgl_tagih_plg' => 'TOTAL',
            'jumlah_pelanggan' => $totalJumlahPelanggan,
            'total_pembayaran' => $totalPembayaran,
            'total_pembayaran_diterima' => $totalPembayaranDiterima,
            'selisih_pembayaran' => $totalSelisihPembayaran
        ]);

        // Urutkan berdasarkan tanggal tagih_plg
        $mergedResults = $mergedResults->sortBy('tgl_tagih_plg');

        return view('home.isolir', compact('mergedResults'));
    }

    public function isolir2()
    {
        // Mengambil data berdasarkan tanggal tagihan dari tabel 'pelanggan', menghitung jumlah total pelanggan dan total pembayaran per tanggal tagihan
        $pelanggan = Pelanggan::select(
            'tgl_tagih_plg',
            DB::raw('harga_paket'),
            DB::raw('COUNT(id_plg) as jumlah_pelanggan'),
            DB::raw('COUNT(id_plg) * harga_paket as total_pembayaran')
        )
            ->groupBy('tgl_tagih_plg', 'harga_paket')  // Mengelompokkan berdasarkan tanggal tagihan dan harga paket
            ->orderBy('tgl_tagih_plg', 'asc')  // Urutkan berdasarkan tanggal tagihan
            ->get();

        // Menggabungkan data berdasarkan tanggal tagih_plg
        $finalResult = $pelanggan->groupBy('tgl_tagih_plg')->map(function ($items) {
            // Totalkan semua pelanggan dan total pembayaran per tanggal
            $jumlahPelanggan = $items->sum('jumlah_pelanggan');
            $totalPembayaran = $items->sum('total_pembayaran');

            return [
                'jumlah_pelanggan' => $jumlahPelanggan,
                'total_pembayaran' => $totalPembayaran,
                'tgl_tagih_plg' => $items->first()->tgl_tagih_plg  // Pastikan kita mendapatkan tgl_tagih_plg yang benar
            ];
        });

        // Mengambil data pembayaran per bulan dan tahun berdasarkan created_at
        $pembayaran = BayarPelanggan::select(
            'tgl_tagih_plg',
            DB::raw('SUM(jumlah_pembayaran) as total_pembayaran_diterima')
        )
            ->whereMonth('created_at', date('m'))  // Mengambil data hanya untuk bulan sekarang berdasarkan created_at
            ->whereYear('created_at', date('Y'))  // Mengambil data hanya untuk tahun sekarang berdasarkan created_at
            ->groupBy('tgl_tagih_plg')  // Mengelompokkan berdasarkan tanggal tagihan
            ->get();

        // Gabungkan hasil dari dua query berdasarkan tanggal tagihan
        $mergedResults = $finalResult->map(function ($pelangganData) use ($pembayaran) {
            $tanggalTagihan = $pelangganData['tgl_tagih_plg'];
            $bayarData = $pembayaran->firstWhere('tgl_tagih_plg', $tanggalTagihan);

            // Menghitung selisih
            $totalPembayaranDiterima = $bayarData ? $bayarData->total_pembayaran_diterima : 0;
            $selisihPembayaran = $pelangganData['total_pembayaran'] - $totalPembayaranDiterima;

            return [
                'tgl_tagih_plg' => $tanggalTagihan,
                'jumlah_pelanggan' => $pelangganData['jumlah_pelanggan'],
                'total_pembayaran' => $pelangganData['total_pembayaran'],
                'total_pembayaran_diterima' => $totalPembayaranDiterima,
                'selisih_pembayaran' => $selisihPembayaran
            ];
        });

        // Menambahkan row untuk total seluruh pembayaran di akhir
        $totalPembayaran = $mergedResults->sum('total_pembayaran');
        $totalPembayaranDiterima = $mergedResults->sum('total_pembayaran_diterima');
        $totalJumlahPelanggan = $mergedResults->sum('jumlah_pelanggan');
        $totalSelisihPembayaran = $totalPembayaran - $totalPembayaranDiterima;

        // Menambahkan baris total ke dalam hasil
        $mergedResults->push([
            'tgl_tagih_plg' => 'TOTAL',
            'jumlah_pelanggan' => $totalJumlahPelanggan,
            'total_pembayaran' => $totalPembayaran,
            'total_pembayaran_diterima' => $totalPembayaranDiterima,
            'selisih_pembayaran' => $totalSelisihPembayaran
        ]);

        // Urutkan berdasarkan tanggal tagih_plg
        $mergedResults = $mergedResults->sortBy('tgl_tagih_plg');

        return view('home.isolir2', compact('mergedResults'));
    }

    public function isolir3()
    {
        // Mengambil data berdasarkan tanggal tagihan dari tabel 'pelanggan', menghitung jumlah total pelanggan dan total pembayaran per tanggal tagihan
        $pelanggan = Pelanggan::select(
            'tgl_tagih_plg',
            DB::raw('harga_paket'),
            DB::raw('COUNT(id_plg) as jumlah_pelanggan'),
            DB::raw('COUNT(id_plg) * harga_paket as total_pembayaran')
        )
            ->groupBy('tgl_tagih_plg', 'harga_paket')  // Mengelompokkan berdasarkan tanggal tagihan dan harga paket
            ->orderBy('tgl_tagih_plg', 'asc')  // Urutkan berdasarkan tanggal tagihan
            ->get();

        // Menggabungkan data berdasarkan tanggal tagih_plg
        $finalResult = $pelanggan->groupBy('tgl_tagih_plg')->map(function ($items) {
            // Totalkan semua pelanggan dan total pembayaran per tanggal
            $jumlahPelanggan = $items->sum('jumlah_pelanggan');
            $totalPembayaran = $items->sum('total_pembayaran');

            return [
                'jumlah_pelanggan' => $jumlahPelanggan,
                'total_pembayaran' => $totalPembayaran,
                'tgl_tagih_plg' => $items->first()->tgl_tagih_plg  // Pastikan kita mendapatkan tgl_tagih_plg yang benar
            ];
        });

        // Mengambil data pembayaran per bulan dan tahun berdasarkan created_at
        $pembayaran = BayarPelanggan::select(
            'tgl_tagih_plg',
            DB::raw('SUM(jumlah_pembayaran) as total_pembayaran_diterima')
        )
            ->whereMonth('created_at', date('m'))  // Mengambil data hanya untuk bulan sekarang berdasarkan created_at
            ->whereYear('created_at', date('Y'))  // Mengambil data hanya untuk tahun sekarang berdasarkan created_at
            ->groupBy('tgl_tagih_plg')  // Mengelompokkan berdasarkan tanggal tagihan
            ->get();

        // Gabungkan hasil dari dua query berdasarkan tanggal tagihan
        $mergedResults = $finalResult->map(function ($pelangganData) use ($pembayaran) {
            $tanggalTagihan = $pelangganData['tgl_tagih_plg'];
            $bayarData = $pembayaran->firstWhere('tgl_tagih_plg', $tanggalTagihan);

            // Menghitung selisih
            $totalPembayaranDiterima = $bayarData ? $bayarData->total_pembayaran_diterima : 0;
            $selisihPembayaran = $pelangganData['total_pembayaran'] - $totalPembayaranDiterima;

            return [
                'tgl_tagih_plg' => $tanggalTagihan,
                'jumlah_pelanggan' => $pelangganData['jumlah_pelanggan'],
                'total_pembayaran' => $pelangganData['total_pembayaran'],
                'total_pembayaran_diterima' => $totalPembayaranDiterima,
                'selisih_pembayaran' => $selisihPembayaran
            ];
        });

        // Menambahkan row untuk total seluruh pembayaran di akhir
        $totalPembayaran = $mergedResults->sum('total_pembayaran');
        $totalPembayaranDiterima = $mergedResults->sum('total_pembayaran_diterima');
        $totalJumlahPelanggan = $mergedResults->sum('jumlah_pelanggan');
        $totalSelisihPembayaran = $totalPembayaran - $totalPembayaranDiterima;

        // Menambahkan baris total ke dalam hasil
        $mergedResults->push([
            'tgl_tagih_plg' => 'TOTAL',
            'jumlah_pelanggan' => $totalJumlahPelanggan,
            'total_pembayaran' => $totalPembayaran,
            'total_pembayaran_diterima' => $totalPembayaranDiterima,
            'selisih_pembayaran' => $totalSelisihPembayaran
        ]);

        // Urutkan berdasarkan tanggal tagih_plg
        $mergedResults = $mergedResults->sortBy('tgl_tagih_plg');

        return view('home.isolir3', compact('mergedResults'));
    }
}

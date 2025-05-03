<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Inventory;
use App\Models\IsolirModel;
use App\Models\Modem;
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


class AdminController extends Controller
{

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
            ->whereYear('tgl_aktivasi', Carbon::now()->year)->where('status', 'selesai')
            ->orderBy('tgl_aktivasi', 'desc')
            ->get();
        $total_pemasangan = $rekap_pemasangan->count();
        $rekap_pemasangan_limited = $rekap_pemasangan->take(5);

        $perbaikan_b = Perbaikan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->get();
        $perbaikan_b_limited = $perbaikan_b->take(5);

        // ini total yang diatas
        $total_per = Perbaikan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        $total_per = Perbaikan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
        $total_perbaikan_b = $total_per->count();

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


        $modem = Modem::all();

        $total_modem = $modem->count();
        $rekap_modem_limited = $modem->take(5);



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

        $pembayaranData = DB::table('rekap_pemasangan')
            ->select(
                DB::raw('MONTH(tgl_aktivasi) as bulan'),
                DB::raw('COUNT(id) as total_user'),
                DB::raw('SUM(harga_paket) as total_pembayaran')
            )
            ->whereYear('tgl_aktivasi', $currentYear)  // Filter berdasarkan tahun saat ini
            ->groupBy(DB::raw('MONTH(tgl_aktivasi)')) // Group berdasarkan bulan
            ->orderBy(DB::raw('MONTH(tgl_aktivasi)'), 'asc') // Urutkan berdasarkan bulan
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




        $rekap_pemasangan_limited = $rekap_pemasangan->take(4);



        //CHART PSB
        $pemasanganData = DB::table('rekap_pemasangan')
            ->select(
                DB::raw('MONTH(tgl_aktivasi) as bulan'),
                DB::raw('COUNT(id) as total_pemasangan'),
                DB::raw('SUM(total_biaya) as total_pendapatan')
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




        // Kirim data ke view
        return view('admin.index', compact(
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
            'totalPendapatan',
            'totalPemasangan',
            'total_WO',
            'inventories',
            'Wo_tampil',
            'perbaikanWO',
            'total_modem',
            'modem',
            'rekap_modem_limited',
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
            'perbaikan_b',
            'total_perbaikan_b',
            'perbaikan_b_limited',





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

    public function detail($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.detail', compact('pelanggan'));
    }



    public function index(Request $request)
    {
        $query = Pelanggan::query();

        // Pengecekan status pembayaran otomatis berdasarkan tanggal tagihan
        $pelanggan_all = Pelanggan::all();
        foreach ($pelanggan_all as $pelanggan) {
            // Ambil tanggal tagih (hari saja)
            $hari_tagih = intval($pelanggan->aktivasi_plg);  // Misal: "25"

            // Ambil bulan dan tahun saat ini
            $currentMonth = now()->month;
            $currentYear = now()->year;

            // Validasi hari tagih agar nilainya valid (antara 1 dan 31)
            if ($hari_tagih >= 1 && $hari_tagih <= 31) {
                // Buat tanggal tagih lengkap (menggabungkan hari, bulan, dan tahun)
                $tgl_tagih = Carbon::createFromDate($currentYear, $currentMonth, $hari_tagih);

                // Jika tanggal tagih sudah lewat di bulan ini, pindahkan ke bulan berikutnya
                if (now()->gt($tgl_tagih)) {
                    $tgl_tagih = $tgl_tagih->addMonth();
                }

                // Bandingkan tgl_tagih dengan waktu sekarang
                if (now()->gt($tgl_tagih) && $pelanggan->status_pembayaran === 'paid') {
                    $pelanggan->status_pembayaran = 'unpaid';
                    $pelanggan->save();
                }
            } else {
                // Log error jika hari tidak valid
                Log::error("Hari tagih tidak valid untuk pelanggan ID: " . $pelanggan->id_plg);
            }
        }

        $query = Pelanggan::query();

        // Mapping status URL ke status database
        $statusMapping = [
            'unpaid' => 'unpaid',
            'paid' => 'paid'
        ];

        // Mapping status database ke URL
        $reverseStatusMapping = array_flip($statusMapping);

        // Filter berdasarkan pencarian jika tombol Cari diklik
        if ($request->input('action') === 'search' && $request->filled('search')) {
            $query->where('nama_plg', 'LIKE', '%' . $request->search . '%')
                ->orWhere('alamat_plg', 'LIKE', '%' . $request->search . '%')
                ->orWhere('harga_paket', 'LIKE', '%' . $request->search . '%');
        }

        // Filter berdasarkan status pembayaran jika tombol Filter diklik
        if ($request->input('action') === 'filter' && $request->filled('status_pembayaran')) {
            $status = $request->status_pembayaran;
            if (array_key_exists($status, $statusMapping)) {
                // Konversi status URL ke format database
                $status_db = $statusMapping[$status];
                $query->where('status_pembayaran', $status_db);
            }
        }

        $pelanggan = $query->get();
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        // Ambil parameter sorting dari request, default ke 'nama_plg' dan 'asc' jika tidak ada parameter
        $sortBy = $request->input('sort_by', 'nama_plg');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Pastikan bahwa sort_direction hanya 'asc' atau 'desc'
        $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'asc';

        // Query data pelanggan dan tambahkan orderBy berdasarkan input sorting
        $bayarpelanggan = Pelanggan::orderBy($sortBy, $sortDirection)->get();


        return view('pelanggan.index', compact('pelanggan', 'status_pembayaran_display', 'bayarpelanggan', 'sortBy', 'sortDirection'));
    }


    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'odp' => 'required',
            'tgl_tagih_plg' => 'tgl_tagih_plg'
        ]);

        $pelanggan = new Pelanggan();
        $pelanggan->id_plg = $request->id_plg;
        $pelanggan->nama_plg = $request->nama_plg;
        $pelanggan->alamat_plg = $request->alamat_plg;
        $pelanggan->no_telepon_plg = $request->no_telepon_plg;
        $pelanggan->aktivasi_plg = $request->aktivasi_plg;
        $pelanggan->paket_plg = $request->paket_plg;
        $pelanggan->harga_paket = $request->harga_paket;
        $pelanggan->keterangan_plg = $request->keterangan_plg;
        $pelanggan->odp = $request->odp;
        $pelanggan->longitude = $request->longitude;
        $pelanggan->latitude = $request->latitude;
        $pelanggan->tgl_tagih_plg = $request->aktivasi_plg;

        // Set status pembayaran awal sebagai 'unpaid'
        $pelanggan->status_pembayaran = 'unpaid';

        $pelanggan->save();

        return redirect()->route('pelanggan.index');
    }

    public function show(string $id) {}


    public function edit(string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, string $id_plg)
    {
        // Ambil data pelanggan yang sudah ada
        $pelanggan = Pelanggan::findOrFail($id_plg);

        // Update data pelanggan yang sudah ada
        $pelanggan->id_plg = $request->id_plg;
        $pelanggan->nama_plg = $request->nama_plg;
        $pelanggan->alamat_plg = $request->alamat_plg;
        $pelanggan->no_telepon_plg = $request->no_telepon_plg;
        $pelanggan->aktivasi_plg = $request->aktivasi_plg;
        $pelanggan->paket_plg = $request->paket_plg;
        $pelanggan->harga_paket = $request->harga_paket;
        $pelanggan->keterangan_plg = $request->keterangan_plg ?? null;
        $pelanggan->odp = $request->odp;
        $pelanggan->tgl_tagih_plg = $request->tgl_tagih_plg;
        $pelanggan->longitude = $request->longitude;
        $pelanggan->latitude = $request->latitude;

        // Simpan data yang sudah diperbarui
        $pelanggan->save();

        return redirect()->route('pelanggan.index');
    }





    public function destroy(string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index');
    }

    public function pelanggan_off($id)
    {
        // Ambil data pelanggan dari tabel pelanggan
        $pelanggan = Pelanggan::find($id);

        DB::table('plg_off')->insert([
            'id_plg' => $pelanggan->id_plg,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'harga_paket' => $pelanggan->harga_paket,
            'odp' => $pelanggan->odp,
            'keterangan_plg' => $pelanggan->keterangan_plg,
            'longitude' => $pelanggan->longitude,
            'latitude' => $pelanggan->latitude,
            'status_pembayaran' => $pelanggan->status_pembayaran,
            'tgl_plg_off' => $pelanggan->created_at->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Hapus data dari tabel pelanggan
        $pelanggan->delete();

        // Redirect ke halaman pelanggan dengan pesan sukses
        return redirect()->route('pelangganof.index')->with('success', 'Pelanggan berhasil dipindahkan ke tabel pelanggan off.');
    }



    public function toggleVisibility($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->is_visible = !$pelanggan->is_visible;
        $pelanggan->last_payment_date = now();
        $pelanggan->save();

        return redirect()->route('pelanggan.detail', $id)->with('status', 'Status visibilitas diubah.');
    }


    public function bayar(Request $request)
    {
        // Ambil input dari form
        $id = $request->input('id');
        $tanggalPembayaran = $request->input('tanggal_pembayaran');
        $metodeTransaksi = $request->input('metode_transaksi'); // Ambil metode transaksi dari form

        // Validasi input
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            'tanggal_pembayaran' => 'required|date',
            'metode_transaksi' => 'required|string',
        ]);

        // Ambil data pelanggan berdasarkan id
        $pelanggan = Pelanggan::findOrFail($id);

        // Simpan data ke tabel bayar_pelanggan
        BayarPelanggan::create([
            'pelanggan_id' => $pelanggan->id,
            'id_plg' => $pelanggan->id_plg ?? null,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'tanggal_pembayaran' => $tanggalPembayaran, // Tanggal pembayaran yang dikirim dari form
            'jumlah_pembayaran' => $pelanggan->harga_paket,
            'metode_transaksi' => $metodeTransaksi, // Simpan metode transaksi yang dipilih
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'paket_plg' => $pelanggan->paket_plg,
        ]);

        // Update status pembayaran pelanggan menjadi 'paid'
        $pelanggan->status_pembayaran = 'paid';
        $pelanggan->save();

        // Redirect ke halaman detail pelanggan dengan pesan sukses
        return redirect()->route('pelanggan.historypembayaran', $id)->with('success', 'Pembayaran berhasil dilakukan.');
    }


    public function historypembayaran($id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        $pembayaran = BayarPelanggan::where('pelanggan_id', $id_plg)->get();

        return view('pelanggan.historypembayaran', compact('pelanggan', 'pembayaran'));
    }


    public function index_bayar(Request $request)
    {
        $search = $request->input('search');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $pembayaran = BayarPelanggan::when($search, function ($query, $search) {
            return $query->where('id', $search)
                ->orWhere('nama_plg', 'like', "%{$search}%");
        })
            ->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('created_at', [$date_start, $date_end]);
            })
            ->get();

        return view('pembayaran.index', compact('pembayaran', 'search', 'date_start', 'date_end'));
    }

    public function export(Request $request, $format)
    {
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $pembayaran = BayarPelanggan::when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
            return $query->whereBetween('created_at', [$date_start, $date_end]);
        })->get();

        if ($format === 'pdf') {
            $pdf = PDF::loadView('pembayaran.pdf', ['pembayaran' => $pembayaran]);
            return $pdf->download('bayar_pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PelangganController($pembayaran), 'bayar_pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }

    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $pembayaran = BayarPelanggan::when($search, function ($query, $search) {
            return $query->where('id', $search)
                ->orWhere('nama_plg', 'like', "%{$search}%");
        })
            ->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('created_at', [$date_start, $date_end]);
            })
            ->get();

        return Excel::download(new PelangganController($pembayaran), 'pembayaran.xlsx');
    }






    public function plg_blm_byr(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mendapatkan pelanggan yang belum membayar di bulan ini
        $pembayaran = DB::table('pelanggan')
            ->leftJoin('pembayaran', 'pelanggan.id_plg', '=', 'pembayaran.pelanggan_id')
            ->whereMonth('pembayaran.tanggal_pembayaran', '!=', now()->month)
            ->whereYear('pembayaran.tanggal_pembayaran', '=', now()->year)
            ->orWhereNull('pembayaran.tanggal_pembayaran')
            ->when($search, function ($query, $search) {
                return $query->where('pelanggan.id_plg', 'like', "%{$search}%")
                    ->orWhere('pelanggan.nama_plg', 'like', "%{$search}%");
            })
            ->select('pelanggan.id_plg', 'pelanggan.nama_plg', 'pelanggan.alamat_plg', 'pembayaran.tanggal_pembayaran', 'pembayaran.jumlah_pembayaran')
            ->get();

        return view('pembayaran.blm_byr', compact('pembayaran'));
    }


    public function getMonthlyPayments()
    {
        // Mengambil data jumlah pembayaran per bulan di tahun ini
        $monthlyPayments = DB::table('bayar_pelanggan')
            ->select(DB::raw('MONTH(tanggal_pembayaran) as month'), DB::raw('SUM(jumlah_pembayaran) as total_pendapatan'))
            ->whereYear('tanggal_pembayaran', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(tanggal_pembayaran)'))
            ->pluck('total_pendapatan', 'month')
            ->toArray();

        // Buat array dengan 12 bulan, isi 0 jika tidak ada data
        $dataPendapatan = array_fill(1, 12, 0);
        foreach ($monthlyPayments as $month => $total) {
            $dataPendapatan[$month] = $total;
        }

        // Kirim data ke view
        return view('chart', [
            'dataPendapatan' => $dataPendapatan,
        ]);
    }

    function teknisi(Request $request)
    {
        $query = Perbaikan::query();

        // Filtering
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where('id_plg', 'like', '%' . $request->search . '%')
                ->orWhere('nama_plg', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sort = $request->get('sort', 'asc');
        $query->orderBy('created_at', $sort);

        $perbaikan = $query->get();

        // Data for charts
        $weeklyData = $query->selectRaw('WEEK(created_at) as week, COUNT(*) as total')
            ->groupBy('week')
            ->pluck('total', 'week');

        $monthlyData = $query->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $yearlyData = $query->selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        return view('perbaikan.index', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }

    function admin()
    {
        return view('index');
    }

    function superadmin()
    {
        return view('index');
    }

    public function belumBayar()
    {
        $pelanggan = Pelanggan::where('status_pembayaran', 'unpaid')->get();
        return view('pelanggan.unpaid', compact('pelanggan'));
    }
}

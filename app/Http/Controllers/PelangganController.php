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
use App\Models\Target;
use App\Models\PembayaranPelanggan;
use App\Models\PengeluaranModel;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\PelangganExport;
use App\Models\Pemberitahuan;
use App\Models\X100c;
use GuzzleHttp\Client;


class PelangganController extends Controller
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
        return view('index', compact(
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


    public function detail($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.detail', compact('pelanggan'));
    }


    public function index(Request $request)
    {
        $query = Pelanggan::whereNotIn('status_pembayaran', ['PSB', 'Reactivasi']);

        // Pengecekan dan update status pembayaran otomatis berdasarkan tanggal tagihan
        $pelanggan_all = Pelanggan::all();

        // Ambil nilai filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $updated_at = $request->input('updated_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $status_pembayaran = $request->input('status_pembayaran');

        // Proses pengecekan status pembayaran otomatis
        foreach ($pelanggan_all as $pelanggan) {
            $hari_tagih = intval($pelanggan->aktivasi_plg);
            $currentMonth = now()->month;
            $currentYear = now()->year;

            if ($hari_tagih >= 1 && $hari_tagih <= 31) {
                $tgl_tagih = Carbon::createFromDate($currentYear, $currentMonth, $hari_tagih);

                if (now()->gt($tgl_tagih)) {
                    $tgl_tagih = $tgl_tagih->addMonth();
                }

                if (now()->gt($tgl_tagih) && $pelanggan->status_pembayaran === 'paid') {
                    $pelanggan->status_pembayaran = 'unpaid';
                    $pelanggan->save();
                }
            }
        }

        $status_pembayaran = request()->has('status_pembayaran') ? explode('&status_pembayaran=', request('status_pembayaran')) : [];

        if (!empty($status_pembayaran)) {
            $query->whereIn('status_pembayaran', $status_pembayaran);
        }

        $pelanggan = $query->get();

        // Filter berdasarkan status pembayaran
        $query->when($request->filled('status_pembayaran'), function ($query) use ($request) {
            $status = $request->input('status_pembayaran');
            if (in_array($status, ['unpaid', 'paid', 'Isolir'])) {
                $query->where('status_pembayaran', $status);
            }
        });

        if (!empty($request->tgl_tagih_plg)) {
            $tglTagih = is_array($request->tgl_tagih_plg) ? $request->tgl_tagih_plg : explode(',', $request->tgl_tagih_plg);

            $query->whereIn('tgl_tagih_plg', $tglTagih)
                ->orderBy('tgl_tagih_plg', 'asc'); // Urutkan dari yang terkecil
        }

        // Filter berdasarkan jumlah pembayaran
        if ($jumlah_pembayaran) {
            $query->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }
        if ($updated_at) {
            $query->whereDate('updated_at', $updated_at);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        $status_pembayaran = $request->input('status_pembayaran');

        // Hitung total pembayaran dan pelanggan berdasarkan filter
        $totalJumlahPembayaranKeseluruhan = $query->sum('harga_paket');
        $totalPelangganKeseluruhan = $query->count();

        // Pembayaran dan pelanggan untuk bulan saat ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');


        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = count($userIdsWithPayments);

        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Ambil data pelanggan dengan pagination
        //$pelanggan = $query->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);
        $pelanggan = $query->paginate(100)->appends($request->all());

        // Tambahan: Ambil data tambahan dan hitung pembayaran masuk berdasarkan filter
        $totalJumlahPembayaranMasuk = BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)
            ->sum('jumlah_pembayaran');

        $userIdsWithPaymentsFiltered = BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayarFiltered = count($userIdsWithPaymentsFiltered);

        $queryfull = Pelanggan::query();

        // Ambil nilai input dari request
        $search = $request->input('search');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg', []);
        $harga_paket = $request->input('harga_paket', []);
        $paket_plg = $request->input('paket_plg', []);
        $status_pembayaran = $request->input('status_pembayaran', []);

        // Pastikan semua nilai input dalam bentuk array jika kosong
        if (!is_array($tgl_tagih_plg)) {
            $tgl_tagih_plg = array_filter(explode(',', $tgl_tagih_plg));
        }
        if (!is_array($harga_paket)) {
            $harga_paket = array_filter(explode(',', $harga_paket));
        }
        if (!is_array($paket_plg)) {
            $paket_plg = array_filter(explode(',', $paket_plg));
        }
        if (!is_array($status_pembayaran)) {
            $status_pembayaran = array_filter(explode(',', $status_pembayaran));
        }

        // Filter berdasarkan pencarian
        if ($search) {
            $queryfull->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%")
                    ->orWhere('status_pembayaran', 'like', "%{$search}%")
                    ->orWhere('paket_plg', 'like', "%{$search}%");
            });
        }

        // Pastikan filter tidak menyebabkan data hilang
        if (!empty($tgl_tagih_plg)) {
            $queryfull->whereIn('tgl_tagih_plg', $tgl_tagih_plg);
        }

        if (!empty($harga_paket)) {
            $queryfull->whereIn('harga_paket', $harga_paket);
        }

        if (!empty($paket_plg)) {
            $queryfull->whereIn('paket_plg', $paket_plg);
        }


        // Jika status_pembayaran difilter, gunakan nilainya, jika tidak pakai default ['isolir', 'paid', 'unpaid']
        if (!empty($status_pembayaran) && is_array($status_pembayaran)) {
            $queryfull->whereIn('status_pembayaran', $status_pembayaran);
        } else {
            $queryfull->whereIn('status_pembayaran', ['isolir', 'paid', 'unpaid']);
        }


        // Filter berdasarkan bulan pembayaran terakhir
        if ($request->filled('bulan_pembayaran')) {
            $bulan = $request->input('bulan_pembayaran');

            $queryfull->whereHas('pembayaranTerakhir', function ($query) use ($bulan) {
                $query->whereMonth('tanggal_pembayaran', $bulan);
            });

            $queryfull->with(['pembayaranTerakhir' => function ($query) use ($bulan) {
                $query->whereMonth('tanggal_pembayaran', $bulan);
            }]);
        } else {
            // Jika tidak ada filter bulan, tetap ambil pembayaran terakhirnya
            $queryfull->with('pembayaranTerakhir');
        }

        // Debug Query
        Log::info("Query SQL:", ['query' => $queryfull->toSql(), 'bindings' => $queryfull->getBindings()]);

        // Paginate hasilnya
        $pelanggan = $queryfull->paginate(100);

        $querySudahBayar = clone $queryfull;
        $queryBelumBayar = clone $queryfull;
        $queryIsolir = clone $queryfull;
        $queryBlock = clone $queryfull;
        $queryUnblock = clone $queryfull;
        $queryfilter = clone $queryfull;


        $totalSudahBayar = $querySudahBayar->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = $queryBelumBayar->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir = $queryIsolir->where('status_pembayaran', 'Isolir')->count();
        $totalBlock = $queryBlock->where('status_pembayaran', 'Block')->count();
        $totalUnblock = $queryUnblock->where('status_pembayaran', 'Unblock')->count();
        $totalPelangganfilter = $queryfilter
            ->whereNotNull('status_pembayaran') // Pastikan tidak NULL
            ->whereNotIn('status_pembayaran', ['PSB', 'Reactivasi']) // Pastikan status sesuai
            ->count();

        $totalPembayaranSudahBayar = $querySudahBayar->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = $queryBelumBayar->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir = $queryIsolir->where('status_pembayaran', 'Isolir')->sum('harga_paket');
        $totalPembayaranBlock = $queryBlock->where('status_pembayaran', 'Block')->sum('harga_paket');
        $totalPembayaranUnblock = $queryUnblock->where('status_pembayaran', 'Unblock')->sum('harga_paket');
        $totalPembayaranSudahBayar = $querySudahBayar->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalJumlahPembayaranfilter =  $queryfilter
            ->whereNotNull('status_pembayaran') // Pastikan tidak NULL
            ->whereNotIn('status_pembayaran', ['PSB', 'Reactivasi']) // Kecualikan PSB & Reactivasi
            ->sum('harga_paket'); // Menjumlahkan harga paket
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;

        // Return view dengan semua data
        return view('pelanggan.index', compact(

            'totalSisa_User',
            'totalSisa_Uang',
            'totalPelangganfilter',
            'totalJumlahPembayaranfilter',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalPelangganBayar',
            'totalJumlahPembayaran',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search',
            'pelanggan',
            'totalSudahBayar',
            'totalBelumBayar',
            'totalIsolir',
            'totalBlock',
            'totalUnblock',
            'totalPembayaranSudahBayar',
            'totalPembayaranBelumBayar',
            'totalPembayaranIsolir',
            'totalPembayaranBlock',
            'totalPembayaranUnblock',

        ));
    }

    public function index_plg_off(Request $request)
    {
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $search = $request->input('search');
        $bulan = $request->bulan;

        $now = Carbon::now();

        // Query dasar
        $queryFilterable = Pelanggan::whereIn('status_pembayaran', ['off', 'Request Pasang']);

        if ($paket_plg) $queryFilterable->where('paket_plg', $paket_plg);
        if ($harga_paket) $queryFilterable->where('harga_paket', $harga_paket);
        if ($tgl_tagih_plg) $queryFilterable->where('tgl_tagih_plg', $tgl_tagih_plg);
        if ($created_at) $queryFilterable->whereDate('created_at', $created_at);

        if ($jumlah_pembayaran) {
            $queryFilterable->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }

        if ($search) {
            $queryFilterable->where(function ($q) use ($search) {
                $q->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%");
            });
        }

        // Filter jika pilih berdasarkan bulan isolir
        if ($bulan) {
            $queryFilterable->whereNotNull('tgl_off')
                ->whereRaw("TIMESTAMPDIFF(MONTH, tgl_off, ?) = ?", [$now, $bulan - 1]);
        }

        $pelanggan = $queryFilterable->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);

        // Hitung total berdasarkan filter
        $totalJumlahPembayaranfilter = $queryFilterable->sum('harga_paket');
        $totalPelangganfilter = $queryFilterable->count();

        // Clone query untuk status tertentu
        $totalSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->count();

        $totalPembayaranSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->sum('harga_paket');

        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;

        // Keseluruhan tanpa filter
        $queryKeseluruhan = Pelanggan::whereIn('status_pembayaran', ['off', 'Request Pasang']);
        $totalJumlahPembayaranKeseluruhan = $queryKeseluruhan->sum('harga_paket');
        $totalPelangganKeseluruhan = $queryKeseluruhan->count();

        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');

        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = $userIdsWithPayments->count();
        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        $totalJumlahPembayaranMasuk = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->sum('jumlah_pembayaran') : 0;

        $totalPelangganBayarFiltered = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->distinct('id_plg')->count('id_plg') : 0;


        $now = Carbon::now();

        $pelangganIsolir = DB::table('pelanggan')
            ->where('status_pembayaran', 'off')
            ->whereNotNull('tgl_off')
            ->get();

        // Fungsi filter per bulan, membandingkan tahun dan bulan secara tepat
        $filterByMonth = function ($p, $n) use ($now) {
            if (empty($p->tgl_off)) return false;
            try {
                $tglOff = Carbon::parse($p->tgl_off);
            } catch (\Exception $e) {
                return false;
            }
            $target = $now->copy()->subMonths($n);
            // Bandingkan format tahun dan bulan dalam 'Ym'
            return $tglOff->format('Ym') === $target->format('Ym');
        };

        $bulan1 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 1));
        $bulan2 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 2));
        $bulan3 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 3));
        $bulan4 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 4));

        $totalBulan1 = $bulan1->sum(function ($p) {
            return is_numeric($p->harga_paket) ? $p->harga_paket : 0;
        });
        $userBulan1 = $bulan1->count();

        $totalBulan2 = $bulan2->sum(function ($p) {
            return is_numeric($p->harga_paket) ? $p->harga_paket : 0;
        });
        $userBulan2 = $bulan2->count();

        $totalBulan3 = $bulan3->sum(function ($p) {
            return is_numeric($p->harga_paket) ? $p->harga_paket : 0;
        });
        $userBulan3 = $bulan3->count();

        $totalBulan4 = $bulan4->sum(function ($p) {
            return is_numeric($p->harga_paket) ? $p->harga_paket : 0;
        });
        $userBulan4 = $bulan4->count();



        return view('pelanggan.index_plg_off', compact(
            'pelanggan',
            'totalJumlahPembayaranfilter',
            'totalPelangganfilter',
            'totalSudahBayar',
            'totalPembayaranSudahBayar',
            'totalBelumBayar',
            'totalPembayaranBelumBayar',
            'totalIsolir',
            'totalPembayaranIsolir',
            'totalSisa_Uang',
            'totalSisa_User',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalJumlahPembayaran',
            'totalPelangganBayar',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search',
            'bulan1',
            'bulan2',
            'bulan3',
            'bulan4',
            'totalBulan1',
            'totalBulan2',
            'totalBulan3',
            'totalBulan4',
            'userBulan1',
            'userBulan2',
            'userBulan3',
            'userBulan4',
            'bulan',
        ));
    }


    public function index_plg_off2(Request $request)
    {
        // Ambil filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $search = $request->input('search');

        // Query dasar untuk pelanggan status 'off' dan 'Request Pasang'
        $queryFilterable = Pelanggan::whereIn('status_pembayaran', ['off', 'Request Pasang']);

        // Terapkan filter
        if ($paket_plg) $queryFilterable->where('paket_plg', $paket_plg);
        if ($harga_paket) $queryFilterable->where('harga_paket', $harga_paket);
        if ($tgl_tagih_plg) $queryFilterable->where('tgl_tagih_plg', $tgl_tagih_plg);
        if ($created_at) $queryFilterable->whereDate('created_at', $created_at);

        if ($jumlah_pembayaran) {
            $queryFilterable->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }

        if ($search) {
            $queryFilterable->where(function ($q) use ($search) {
                $q->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%");
            });
        }

        // Data utama untuk ditampilkan (pagination)
        $pelanggan = $queryFilterable->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);

        // Hitung total berdasarkan filter
        $totalJumlahPembayaranfilter = $queryFilterable->sum('harga_paket');
        $totalPelangganfilter = $queryFilterable->count();

        // Clone query untuk status tertentu
        $totalSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->count();

        $totalPembayaranSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->sum('harga_paket');

        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;

        // Perhitungan keseluruhan tanpa filter
        $queryKeseluruhan = Pelanggan::whereIn('status_pembayaran', ['off', 'Request Pasang']);
        $totalJumlahPembayaranKeseluruhan = $queryKeseluruhan->sum('harga_paket');
        $totalPelangganKeseluruhan = $queryKeseluruhan->count();

        // Pembayaran masuk bulan ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');

        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = $userIdsWithPayments->count();
        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Pembayaran berdasarkan tanggal filter
        $totalJumlahPembayaranMasuk = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->sum('jumlah_pembayaran') : 0;

        $totalPelangganBayarFiltered = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->distinct('id_plg')->count('id_plg') : 0;

        return view('pelanggan.index_plg_off', compact(
            'pelanggan',
            'totalJumlahPembayaranfilter',
            'totalPelangganfilter',
            'totalSudahBayar',
            'totalPembayaranSudahBayar',
            'totalBelumBayar',
            'totalPembayaranBelumBayar',
            'totalIsolir',
            'totalPembayaranIsolir',
            'totalSisa_Uang',
            'totalSisa_User',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalJumlahPembayaran',
            'totalPelangganBayar',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search'
        ));
    }





    public function reactivasi(Request $request)
    {
        // Ambil filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $search = $request->input('search');

        // Query dasar untuk pelanggan status 'off' dan 'Request Pasang'
        $queryFilterable = Pelanggan::whereIn('status_pembayaran', ['Reactivasi', 'Request Pasang']);

        // Terapkan filter
        if ($paket_plg) $queryFilterable->where('paket_plg', $paket_plg);
        if ($harga_paket) $queryFilterable->where('harga_paket', $harga_paket);
        if ($tgl_tagih_plg) $queryFilterable->where('tgl_tagih_plg', $tgl_tagih_plg);
        if ($created_at) $queryFilterable->whereDate('created_at', $created_at);

        if ($jumlah_pembayaran) {
            $queryFilterable->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }

        if ($search) {
            $queryFilterable->where(function ($q) use ($search) {
                $q->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%");
            });
        }

        // Data utama untuk ditampilkan (pagination)
        $pelanggan = $queryFilterable->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);

        // Hitung total berdasarkan filter
        $totalJumlahPembayaranfilter = $queryFilterable->sum('harga_paket');
        $totalPelangganfilter = $queryFilterable->count();

        // Clone query untuk status tertentu
        $totalSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->count();

        $totalPembayaranSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->sum('harga_paket');

        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;

        // Perhitungan keseluruhan tanpa filter
        $queryKeseluruhan = Pelanggan::whereIn('status_pembayaran', ['off', 'Request Pasang']);
        $totalJumlahPembayaranKeseluruhan = $queryKeseluruhan->sum('harga_paket');
        $totalPelangganKeseluruhan = $queryKeseluruhan->count();

        // Pembayaran masuk bulan ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');

        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = $userIdsWithPayments->count();
        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Pembayaran berdasarkan tanggal filter
        $totalJumlahPembayaranMasuk = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->sum('jumlah_pembayaran') : 0;

        $totalPelangganBayarFiltered = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->distinct('id_plg')->count('id_plg') : 0;

        return view('pelanggan.reactivasi', compact(
            'pelanggan',
            'totalJumlahPembayaranfilter',
            'totalPelangganfilter',
            'totalSudahBayar',
            'totalPembayaranSudahBayar',
            'totalBelumBayar',
            'totalPembayaranBelumBayar',
            'totalIsolir',
            'totalPembayaranIsolir',
            'totalSisa_Uang',
            'totalSisa_User',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalJumlahPembayaran',
            'totalPelangganBayar',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search'
        ));
    }


    public function isolir(Request $request)
    {
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $search = $request->input('search');
        $bulan = $request->bulan;

        $now = Carbon::now();

        // Query dasar
        $queryFilterable = Pelanggan::whereIn('status_pembayaran', ['isolir', 'Request Pasang']);

        if ($paket_plg) $queryFilterable->where('paket_plg', $paket_plg);
        if ($harga_paket) $queryFilterable->where('harga_paket', $harga_paket);
        if ($tgl_tagih_plg) $queryFilterable->where('tgl_tagih_plg', $tgl_tagih_plg);
        if ($created_at) $queryFilterable->whereDate('created_at', $created_at);

        if ($jumlah_pembayaran) {
            $queryFilterable->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }

        if ($search) {
            $queryFilterable->where(function ($q) use ($search) {
                $q->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%");
            });
        }

        // Filter jika pilih berdasarkan bulan isolir
        if ($bulan) {
            $queryFilterable->whereNotNull('tgl_isolir')
                ->whereRaw("TIMESTAMPDIFF(MONTH, tgl_isolir, ?) = ?", [$now, $bulan - 1]);
        }

        $pelanggan = $queryFilterable->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);

        // Hitung total berdasarkan filter
        $totalJumlahPembayaranfilter = $queryFilterable->sum('harga_paket');
        $totalPelangganfilter = $queryFilterable->count();

        // Clone query untuk status tertentu
        $totalSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->count();

        $totalPembayaranSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->sum('harga_paket');

        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;

        // Keseluruhan tanpa filter
        $queryKeseluruhan = Pelanggan::whereIn('status_pembayaran', ['off', 'Request Pasang']);
        $totalJumlahPembayaranKeseluruhan = $queryKeseluruhan->sum('harga_paket');
        $totalPelangganKeseluruhan = $queryKeseluruhan->count();

        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');

        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = $userIdsWithPayments->count();
        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        $totalJumlahPembayaranMasuk = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->sum('jumlah_pembayaran') : 0;

        $totalPelangganBayarFiltered = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->distinct('id_plg')->count('id_plg') : 0;

        $sekarang = Carbon::now();

        $pelangganIsolir = DB::table('pelanggan')
            ->where('status_pembayaran', 'isolir')
            ->whereNotNull('tgl_isolir')  // ini udah ngefilter yang tgl_isolir null
            ->get();

        // Filter yang valid: pastikan tgl_isolir ada dan valid sebelum filter bulan
        $filterByMonth = function ($p, $n) use ($sekarang) {
            if (!$p->tgl_isolir) return false; // Kalau tgl_isolir kosong, jangan masuk
            return Carbon::parse($p->tgl_isolir)->diffInMonths($sekarang) === $n;
        };

        $filterByMinMonth = function ($p, $n) use ($sekarang) {
            if (!$p->tgl_isolir) return false; // Kalau tgl_isolir kosong, jangan masuk
            return Carbon::parse($p->tgl_isolir)->diffInMonths($sekarang) >= $n;
        };

        $bulan1 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 0)); // bulan ini
        $bulan2 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 1)); // 1 bulan lalu
        $bulan3 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 2)); // 2 bulan lalu
        $bulan4 = $pelangganIsolir->filter(fn($p) => $filterByMonth($p, 3)); // 3 bulan lalu (bukan >=)


        $totalBulan1 = $bulan1->sum('harga_paket');
        $userBulan1 = $bulan1->count();

        $totalBulan2 = $bulan2->sum('harga_paket');
        $userBulan2 = $bulan2->count();

        $totalBulan3 = $bulan3->sum('harga_paket');
        $userBulan3 = $bulan3->count();

        $totalBulan4 = $bulan4->sum('harga_paket');
        $userBulan4 = $bulan4->count();

        $bulan = 1; // contoh parameter

        return view('pelanggan.isolir', compact(
            'pelanggan',
            'totalJumlahPembayaranfilter',
            'totalPelangganfilter',
            'totalSudahBayar',
            'totalPembayaranSudahBayar',
            'totalBelumBayar',
            'totalPembayaranBelumBayar',
            'totalIsolir',
            'totalPembayaranIsolir',
            'totalSisa_Uang',
            'totalSisa_User',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalJumlahPembayaran',
            'totalPelangganBayar',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search',
            'bulan1',
            'bulan2',
            'bulan3',
            'bulan4',
            'totalBulan1',
            'totalBulan2',
            'totalBulan3',
            'userBulan4',
            'totalBulan4',
            'userBulan1',
            'userBulan2',
            'userBulan3',

            'bulan',
        ));
    }







    public function unblock(Request $request)
    {
        // Ambil semua pelanggan
        $query = Pelanggan::query();
        $query1 = Pelanggan::query();



        // Pengecekan dan update status pembayaran otomatis berdasarkan tanggal tagihan
        $pelanggan_all = Pelanggan::all();

        // Ambil nilai filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $updated_at = $request->input('updated_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');

        // Proses pengecekan status pembayaran otomatis
        foreach ($pelanggan_all as $pelanggan) {
            $hari_tagih = intval($pelanggan->aktivasi_plg);
            $currentMonth = now()->month;
            $currentYear = now()->year;

            if ($hari_tagih >= 1 && $hari_tagih <= 31) {
                $tgl_tagih = Carbon::createFromDate($currentYear, $currentMonth, $hari_tagih);

                if (now()->gt($tgl_tagih)) {
                    $tgl_tagih = $tgl_tagih->addMonth();
                }

                if (now()->gt($tgl_tagih) && $pelanggan->status_pembayaran === 'paid') {
                    $pelanggan->status_pembayaran = 'unpaid';
                    $pelanggan->save();
                }
            }
        }

        // Mengambil pelanggan yang tidak dalam status Isolir atau Block
        $query = Pelanggan::whereIn('status_pembayaran', ['Unblock']);
        $query_tnppsb = Pelanggan::whereNot('status_pembayaran', ['PSB', 'Reactivasi']);

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $status = $request->input('status_pembayaran');
            $query->where('status_pembayaran', $status === 'unpaid' ? 'unpaid' : 'paid');
        }

        // Filter berdasarkan tanggal tagih
        if ($tgl_tagih_plg) {
            $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        }

        // Filter berdasarkan jumlah pembayaran
        if ($jumlah_pembayaran) {
            $query->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }
        if ($updated_at) {
            $query->whereDate('updated_at', $updated_at);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%")
                    ->orWhere('status_pembayaran', 'like', "%{$search}%");
            });
        }

        // Hitung total pembayaran dan pelanggan berdasarkan filter
        $totalJumlahPembayaranKeseluruhan = $query_tnppsb->sum('harga_paket');
        $totalPelangganKeseluruhan = $query_tnppsb->count();

        // Pembayaran dan pelanggan untuk bulan saat ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');


        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = count($userIdsWithPayments);

        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Ambil data pelanggan dengan pagination
        $pelanggan = $query->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);

        // Tambahan: Ambil data tambahan dan hitung pembayaran masuk berdasarkan filter
        $totalJumlahPembayaranMasuk = BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)
            ->sum('jumlah_pembayaran');

        $userIdsWithPaymentsFiltered = BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayarFiltered = count($userIdsWithPaymentsFiltered);

        // Tambahan: Menghitung total pembayaran sesuai status
        $queryfull = Pelanggan::query();

        // Filter berdasarkan tgl_tagih_plg
        if ($tgl_tagih_plg) {
            $queryfull->where('tgl_tagih_plg', $tgl_tagih_plg);
        }

        // Filter berdasarkan harga_paket
        if ($harga_paket) {
            $queryfull->where('harga_paket', $harga_paket);
        }

        // Filter berdasarkan paket_plg
        if ($paket_plg) {
            $queryfull->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan status_pembayaran
        if ($request->filled('status_pembayaran')) {
            $status = $request->input('status_pembayaran');
            $queryfull->where('status_pembayaran', $status);
        }


        $querySudahBayar = clone $queryfull;
        $queryBelumBayar = clone $queryfull;
        $queryIsolir = clone $queryfull;
        $queryBlock = clone $queryfull;
        $queryUnblock = clone $queryfull;
        $queryfilter = clone $queryfull;

        $totalSudahBayar = $querySudahBayar->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = $queryBelumBayar->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir = $queryIsolir->where('status_pembayaran', 'Isolir')->count();
        $totalBlock = $queryBlock->where('status_pembayaran', 'Block')->count();
        $totalUnblock = $queryUnblock->where('status_pembayaran', 'Unblock')->count();
        $totalPelangganfilter = $queryfilter->whereNotNull('status_pembayaran')->count();

        $totalPembayaranSudahBayar = $querySudahBayar->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = $queryBelumBayar->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir = $queryIsolir->where('status_pembayaran', 'Isolir')->sum('harga_paket');
        $totalPembayaranBlock = $queryBlock->where('status_pembayaran', 'Block')->sum('harga_paket');
        $totalPembayaranUnblock = $queryUnblock->where('status_pembayaran', 'Unblock')->sum('harga_paket');
        $totalJumlahPembayaranfilter = $queryfilter->whereNotNull('status_pembayaran')->sum('harga_paket');


        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;


        // Return view dengan semua data
        return view('pelanggan.unblock', compact(
            'totalSisa_User',
            'totalSisa_Uang',
            // Return view dengan semua data

            'totalPelangganfilter',
            'totalJumlahPembayaranfilter',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalPelangganBayar',
            'totalJumlahPembayaran',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search',
            'pelanggan',
            'totalSudahBayar',
            'totalBelumBayar',
            'totalIsolir',
            'totalBlock',
            'totalUnblock',
            'totalPembayaranSudahBayar',
            'totalPembayaranBelumBayar',
            'totalPembayaranIsolir',
            'totalPembayaranBlock',
            'totalPembayaranUnblock',

        ));
    }




    public function block(Request $request)
    {
        // Ambil semua pelanggan
        $query = Pelanggan::query();
        $query1 = Pelanggan::query();



        // Pengecekan dan update status pembayaran otomatis berdasarkan tanggal tagihan
        $pelanggan_all = Pelanggan::all();

        // Ambil nilai filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran'); // Filter jumlah pembayaran

        // Proses pengecekan status pembayaran otomatis
        foreach ($pelanggan_all as $pelanggan) {
            $hari_tagih = intval($pelanggan->aktivasi_plg);
            $currentMonth = now()->month;
            $currentYear = now()->year;

            if ($hari_tagih >= 1 && $hari_tagih <= 31) {
                $tgl_tagih = Carbon::createFromDate($currentYear, $currentMonth, $hari_tagih);

                if (now()->gt($tgl_tagih)) {
                    $tgl_tagih = $tgl_tagih->addMonth();
                }

                if (now()->gt($tgl_tagih) && $pelanggan->status_pembayaran === 'paid') {
                    $pelanggan->status_pembayaran = 'unpaid';
                    $pelanggan->save();
                }
            }
        }

        // Mengambil pelanggan yang tidak dalam status Isolir atau Block
        $query = Pelanggan::whereIn('status_pembayaran', ['Block']);
        $query_tnppsb = Pelanggan::whereNot('status_pembayaran', ['PSB', 'Reactivasi']);

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $status = $request->input('status_pembayaran');
            $query->where('status_pembayaran', $status === 'unpaid' ? 'unpaid' : 'paid');
        }

        // Filter berdasarkan tanggal tagih
        if ($tgl_tagih_plg) {
            $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        }

        // Filter berdasarkan jumlah pembayaran
        if ($jumlah_pembayaran) {
            $query->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%")
                    ->orWhere('status_pembayaran', 'like', "%{$search}%");
            });
        }

        // Hitung total pembayaran dan pelanggan berdasarkan filter
        $totalJumlahPembayaranKeseluruhan = $query_tnppsb->sum('harga_paket');
        $totalPelangganKeseluruhan = $query_tnppsb->count();

        // Pembayaran dan pelanggan untuk bulan saat ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');


        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = count($userIdsWithPayments);

        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Ambil data pelanggan dengan pagination
        $pelanggan = $query->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);

        // Tambahan: Ambil data tambahan dan hitung pembayaran masuk berdasarkan filter
        $totalJumlahPembayaranMasuk = BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)
            ->sum('jumlah_pembayaran');

        $userIdsWithPaymentsFiltered = BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayarFiltered = count($userIdsWithPaymentsFiltered);

        // Tambahan: Menghitung total pembayaran sesuai status
        $queryfull = Pelanggan::query();

        // Filter berdasarkan tgl_tagih_plg
        if ($tgl_tagih_plg) {
            $queryfull->where('tgl_tagih_plg', $tgl_tagih_plg);
        }

        // Filter berdasarkan harga_paket
        if ($harga_paket) {
            $queryfull->where('harga_paket', $harga_paket);
        }

        // Filter berdasarkan paket_plg
        if ($paket_plg) {
            $queryfull->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan status_pembayaran
        if ($request->filled('status_pembayaran')) {
            $status = $request->input('status_pembayaran');
            $queryfull->where('status_pembayaran', $status);
        }


        $querySudahBayar = clone $queryfull;
        $queryBelumBayar = clone $queryfull;
        $queryIsolir = clone $queryfull;
        $queryBlock = clone $queryfull;
        $queryUnblock = clone $queryfull;
        $queryfilter = clone $queryfull;

        $totalSudahBayar = $querySudahBayar->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = $queryBelumBayar->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir = $queryIsolir->where('status_pembayaran', 'Isolir')->count();
        $totalBlock = $queryBlock->where('status_pembayaran', 'Block')->count();
        $totalUnblock = $queryUnblock->where('status_pembayaran', 'Unblock')->count();
        $totalPelangganfilter = $queryfilter->whereNotNull('status_pembayaran')->count();

        $totalPembayaranSudahBayar = $querySudahBayar->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = $queryBelumBayar->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir = $queryIsolir->where('status_pembayaran', 'Isolir')->sum('harga_paket');
        $totalPembayaranBlock = $queryBlock->where('status_pembayaran', 'Block')->sum('harga_paket');
        $totalPembayaranUnblock = $queryUnblock->where('status_pembayaran', 'Unblock')->sum('harga_paket');
        $totalJumlahPembayaranfilter = $queryfilter->whereNotNull('status_pembayaran')->sum('harga_paket');
        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;


        // Return view dengan semua data
        return view('pelanggan.psb', compact(
            'totalSisa_User',
            'totalSisa_Uang',
            // Return view dengan semua data

            'totalPelangganfilter',
            'totalJumlahPembayaranfilter',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalPelangganBayar',
            'totalJumlahPembayaran',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search',
            'pelanggan',
            'totalSudahBayar',
            'totalBelumBayar',
            'totalIsolir',
            'totalBlock',
            'totalUnblock',
            'totalPembayaranSudahBayar',
            'totalPembayaranBelumBayar',
            'totalPembayaranIsolir',
            'totalPembayaranBlock',
            'totalPembayaranUnblock',

        ));
    }

    public function toggleStatus(Request $request, $id)
    {
        // Cek apakah user memiliki role super_admin
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah status.');
        }

        // Cari pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($id);

        // Cek status yang diterima dari input tersembunyi
        if ($request->input('status') === 'block') {
            // Ubah status pembayaran menjadi "Block"
            $pelanggan->status_pembayaran = 'Block';
            $message = 'Status pembayaran berhasil diubah menjadi Block.';
        } else {
            // Ubah status pembayaran menjadi "Nonblock"
            $pelanggan->status_pembayaran = 'UnBlock';
            $message = 'Status pembayaran berhasil diubah menjadi Nonblock.';
        }

        // Simpan perubahan
        $pelanggan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('pelanggan.isolir')->with('success', $message);
    }


    public function toIsolir($id)
    {
        // Cari pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($id);

        // Ubah status pembayaran menjadi "Isolir"
        $pelanggan->status_pembayaran = 'Block';

        // Simpan perubahan
        $pelanggan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('pelanggan.isolir')->with('success', 'Status pembayaran berhasil diubah menjadi Isolir.');
    }


    public function toUnblock($id)
    {
        // Cari pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($id);

        // Ubah status pembayaran menjadi "Isolir"
        $pelanggan->status_pembayaran = 'Unblock';

        // Simpan perubahan
        $pelanggan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('pelanggan.isolir')->with('success', 'Status pembayaran berhasil diubah menjadi Unblock.');
    }

    private function checkAndMoveToIsolirr($pelanggan)
    {
        // Isolir pelanggan jika sudah lewat batas tagihan
        if ($pelanggan->status_pembayaran === 'unpaid') {
            IsolirModel::create([
                'id_plg' => $pelanggan->id_plg,
                'nama_plg' => $pelanggan->nama_plg,
                'alamat_plg' => $pelanggan->alamat_plg,
                'no_telepon_plg' => $pelanggan->no_telepon_plg,
                'aktivasi_plg' => $pelanggan->aktivasi_plg,
                'paket_plg' => $pelanggan->paket_plg,
                'harga_paket' => $pelanggan->harga_paket,
                'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
                'keterangan_plg' => $pelanggan->keterangan_plg,
                'odp' => $pelanggan->odp,
                'longitude' => $pelanggan->longitude,
                'latitude' => $pelanggan->latitude,
                'status_pembayaran' => $pelanggan->status_pembayaran,
            ]);

            // Hapus pelanggan dari tabel pelanggan
            $pelanggan->delete();
        }
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
        $pelanggan->tgl_tagih_plg = \Carbon\Carbon::parse($request->aktivasi_plg)->format('d');

        // Set status pembayaran awal sebagai 'unpaid'
        $pelanggan->status_pembayaran = 'PSB';

        $pelanggan->save();

        return redirect()->route('pelanggan.psb')->with('success', 'Pelanggan Baru berhasil Ditambahkan .');
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

        // Simpan data lama untuk perbandingan
        $oldData = $pelanggan->toArray();

        // Daftar field yang ingin diperiksa perubahan
        $fields = [
            'id_plg',
            'nama_plg',
            'alamat_plg',
            'no_telepon_plg',
            'aktivasi_plg',
            'paket_plg',
            'harga_paket',
            'status_pembayaran',
            'keterangan_plg',
            'odp',
            'tgl_tagih_plg',
            'longitude',
            'latitude'
        ];

        // Cek perubahan
        $changedFields = [];
        foreach ($fields as $field) {
            $newValue = $request->$field;
            $oldValue = $oldData[$field] ?? null;

            if ($newValue != $oldValue) {
                $changedFields[$field] = $newValue;
            }

            // Update field ke model
            $pelanggan->$field = $newValue;
        }

        // Simpan data ke database
        $pelanggan->save();

        // Siapkan log dengan urutan: id_plg, nama_plg, baru lainnya
        if (!empty($changedFields)) {
            $logData = [
                'id_plg' => $pelanggan->id_plg,
                'nama_plg' => $pelanggan->nama_plg,
            ];

            foreach ($changedFields as $key => $value) {
                if (!in_array($key, ['id_plg', 'nama_plg'])) {
                    $logData[$key] = $value;
                }
            }

            $logData['updated_by'] = Auth::user()->name ?? 'Guest';

            logActivity('Update data pelanggan', 'Pelanggan', $logData);
        }

        return redirect()->back()->with('success', 'Pelanggan atas nama: ' . $pelanggan->nama_plg . ' berhasil di Edit');
    }
    public function destroy(string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        $pelanggan->delete();

        // return redirect()->route('pelanggan.index');

        return redirect()->back()->with('success', 'Aksi berhasil dilakukan.');
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
            'status_pembayaran' => $pelanggan->status_pembayaran = 'off',
            //   'tgl_plg_off' => $pelanggan->created_at->format('Y-m-d'),
            'tgl_plg_off' => now(),
            'created_at' => $pelanggan->created_at->format('Y-m-d'),
            'updated_at' => now(),
        ]);

        // Hapus data dari tabel pelanggan
        $pelanggan->delete();

        // Redirect ke halaman pelanggan dengan pesan sukses
        // return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dipindahkan ke tabel pelanggan off.');

        return redirect()->route('pelanggan.index', $pelanggan->id)
            ->with('success', 'Pelanggan Atas Nama : '  . $pelanggan->nama_plg .  ' berhasil dipindahkan Menjdi pelanggan OFF');
    }





    public function toggleVisibility($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->is_visible = !$pelanggan->is_visible;
        $pelanggan->last_payment_date = now();
        $pelanggan->save();

        return redirect()->route('pelanggan.detail', $id)->with('status', 'Status visibilitas diubah.');
    }


    public function bayar3(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            'metode_transaksi' => 'required|string',
            'untuk_pembayaran' => 'required|string',
            'tanggal_pembayaran' => 'nullable|date_format:Y-m' // nullable untuk membolehkan tidak diisi
        ]);

        // Ambil data pelanggan berdasarkan id
        $pelanggan = Pelanggan::findOrFail($request->id);

        // Cek apakah admin memilih bulan pembayaran
        if ($request->filled('tanggal_pembayaran')) {
            $tanggalPembayaran = $request->tanggal_pembayaran . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = $request->tanggal_pembayaran; // Simpan hanya Y-m (untuk pengecekan bulan)
        } else {
            $tanggalPembayaran = Carbon::now()->format('Y-m') . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = Carbon::now()->format('Y-m'); // Simpan hanya Y-m (untuk pengecekan bulan)
        }

        // Cek apakah sudah ada pembayaran di bulan yang sama
        $existingPayment = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->where('tanggal_pembayaran', 'like', $bulanPembayaran . '%')
            ->exists();

        if ($existingPayment) {
            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                ->with('alert', 'Gagal!! Karena Pembayaran untuk bulan ini sudah dilakukan untuk Pelanggan ' . $pelanggan->nama_plg . '.');
        }

        // Ambil data admin yang login atau default ke 'Unknown Admin' jika tidak ada
        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        // Simpan data ke tabel bayar_pelanggan
        $payment = BayarPelanggan::create([
            'pelanggan_id' => $pelanggan->id,
            'id_plg' => $pelanggan->id_plg ?? null,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'jumlah_pembayaran' => $pelanggan->harga_paket,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'metode_transaksi' => $request->metode_transaksi,
            'untuk_pembayaran' => $request->untuk_pembayaran,
            'keterangan_plg' => $request->keterangan_plg,
            'tanggal_pembayaran' => $tanggalPembayaran, // Simpan tanggal pembayaran
            'admin_name' => $adminName,
        ]);

        // Update status pembayaran pelanggan menjadi 'paid'
        $pelanggan->status_pembayaran = 'paid';
        $pelanggan->save();

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($payment);

        // Redirect ke halaman history pembayaran dengan pesan sukses
        return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
            ->with('success', 'Pembayaran berhasil dilakukan untuk pelanggan ' . $pelanggan->nama_plg . '.');
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            'metode_transaksi' => 'required|string',
            'untuk_pembayaran' => 'required|string',
            'tanggal_pembayaran' => 'nullable|date_format:Y-m'
        ]);

        $pelanggan = Pelanggan::findOrFail($request->id);

        if ($request->filled('tanggal_pembayaran')) {
            $tanggalPembayaran = $request->tanggal_pembayaran . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = $request->tanggal_pembayaran;
        } else {
            $tanggalPembayaran = Carbon::now()->format('Y-m') . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = Carbon::now()->format('Y-m');
        }

        $existingPayment = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->where('tanggal_pembayaran', 'like', $bulanPembayaran . '%')
            ->exists();

        if ($existingPayment) {
            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                ->with('alert', 'Gagal!! Karena Pembayaran untuk bulan ini sudah dilakukan untuk Pelanggan ' . $pelanggan->nama_plg . '.');
        }

        // 🛡️ Cek apakah bulan sebelumnya sudah dibayar
        $bulanPembayaranCarbon = Carbon::createFromFormat('Y-m', $bulanPembayaran)->startOfMonth();

        $pembayaranTerakhir = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tanggal_pembayaran', 'desc')
            ->first();

        if ($pembayaranTerakhir) {
            $lastPaidMonth = Carbon::parse($pembayaranTerakhir->tanggal_pembayaran)->startOfMonth();
            $expectedNextPaymentMonth = $lastPaidMonth->copy()->addMonth();

            if ($bulanPembayaranCarbon->gt($expectedNextPaymentMonth)) {
                return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                    ->with('alert', 'Gagal!! Pelanggan belum bayar bulan sebelumnya. Harus bayar bulan '
                        . $expectedNextPaymentMonth->locale('id')->isoFormat('MMMM Y') . ' dulu.');
            }
        }

        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        $payment = BayarPelanggan::create([
            'pelanggan_id' => $pelanggan->id,
            'id_plg' => $pelanggan->id_plg ?? null,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'jumlah_pembayaran' => $pelanggan->harga_paket,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'metode_transaksi' => $request->metode_transaksi,
            'untuk_pembayaran' => $request->untuk_pembayaran,
            'keterangan_plg' => $request->keterangan_plg,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'admin_name' => $adminName,
        ]);


        // Ambil data pembayaran terakhir pelanggan
        $pembayaranTerakhir = $pelanggan->pembayaranTerakhir; // Pastikan relasi ini tersedia

        // Cek apakah ada pembayaran terakhir
        if ($pembayaranTerakhir) {
            $bulanTerakhir = Carbon::parse($pembayaranTerakhir->tanggal)->format('Ym'); // Contoh: 202504
            $bulanSekarang = Carbon::now()->format('Ym');

            // Jika bulan terakhir >= bulan sekarang, maka paid, else isolir
            $pelanggan->status_pembayaran = ($bulanTerakhir >= $bulanSekarang) ? 'paid' : 'isolir';
        } else {
            // Kalau belum pernah bayar, langsung anggap isolir
            $pelanggan->status_pembayaran = 'isolir';
        }

        $pelanggan->save();


        // ✅ Log aktivitas pembayaran
        logActivity('Melakukan pembayaran pelanggan', 'Pembayaran', [
            'pelanggan_id' => $pelanggan->id,
            'nama' => $pelanggan->nama_plg,
            'jumlah' => $pelanggan->harga_paket,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'metode' => $request->metode_transaksi,
            'untuk' => $request->untuk_pembayaran,
        ]);

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($payment);

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil dilakukan untuk pelanggan ' . $pelanggan->nama_plg . '.');
    }

    public function bayar2(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            'metode_transaksi' => 'required|string',
            'untuk_pembayaran' => 'required|string',
            'tanggal_pembayaran' => 'nullable|date_format:Y-m' // nullable untuk membolehkan tidak diisi
            //'tanggal_pembayaran' => 'nullable|date_format:Y-m-d' // nullable untuk membolehkan tidak diisi
        ]);

        // Ambil data pelanggan berdasarkan id
        $pelanggan = Pelanggan::findOrFail($request->id);

        if ($request->filled('tanggal_pembayaran')) {
            $tanggalPembayaran = $request->tanggal_pembayaran . '-' . $pelanggan->tgl_tagih_plg;
            // $tanggalPembayaran = $request->tanggal_pembayaran ;
            $bulanPembayaran = $request->tanggal_pembayaran; // Simpan hanya Y-m (untuk pengecekan bulan)
        } else {
            $tanggalPembayaran = Carbon::now()->format('Y-m') . '-' . $pelanggan->tgl_tagih_plg;
            // $tanggalPembayaran = Carbon::now()->format('Y-m-d');
            $bulanPembayaran = Carbon::now()->format('Y-m-d'); // Simpan hanya Y-m (untuk pengecekan bulan)
        }

        // Cek apakah sudah ada pembayaran di bulan yang sama
        $existingPayment = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->where('tanggal_pembayaran', 'like', $bulanPembayaran . '%')
            ->exists();

        if ($existingPayment) {
            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                ->with('alert', 'Gagal!! Karena Pembayaran untuk bulan ini sudah dilakukan untuk Pelanggan ' . $pelanggan->nama_plg . '.');
        }

        // Ambil data admin yang login atau default ke 'Unknown Admin' jika tidak ada
        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        // Simpan data ke tabel bayar_pelanggan
        $payment = BayarPelanggan::create([
            'pelanggan_id' => $pelanggan->id,
            'id_plg' => $pelanggan->id_plg ?? null,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'jumlah_pembayaran' => $pelanggan->harga_paket,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'metode_transaksi' => $request->metode_transaksi,
            'untuk_pembayaran' => $request->untuk_pembayaran,
            'keterangan_plg' => $request->keterangan_plg,
            'tanggal_pembayaran' => $tanggalPembayaran, // Simpan tanggal pembayaran
            'admin_name' => $adminName,
        ]);
        // Tentukan status pembayaran berdasarkan tanggal pembayaran
        $today = Carbon::now()->format('Y-m-d'); // Tanggal sekarang
        $pelanggan->status_pembayaran = ($tanggalPembayaran >= $today) ? 'paid' : 'isolir';
        $pelanggan->save();

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($payment);

        // Redirect ke halaman history pembayaran dengan pesan sukses
        return redirect()->back('pembayaran_mudah.index', $pelanggan->id)
            ->with('success', 'Pembayaran berhasil dilakukan untuk pelanggan ' . $pelanggan->nama_plg . '.');
    }


    private function sendTelegramNotification($payment)
    {
        // $token = 7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY
        // $chat_id = '-1002333302498';


        $token = '';
        $chat_id = '-1002333302498';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        // Inisialisasi Query
        $query_tnppsb = Pelanggan::whereNot('status_pembayaran', ['PSB']);

        // Menghitung total pembayaran & pelanggan
        $totalJumlahPembayaranKeseluruhan = $query_tnppsb->sum('harga_paket');
        $totalPelangganKeseluruhan = $query_tnppsb->count();

        $totalJumlahPaid = $query_tnppsb->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPelangganPaid = $query_tnppsb->where('status_pembayaran', 'paid')->count();

        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPaid;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganPaid;

        // Menyiapkan pesan untuk Telegram
        $message =
            "🌙 *Notifikasi Pembayaran Baru* 🕌\n" .
            "==============================\n" .
            "👤 *Pelanggan :* {$payment->nama_plg}\n" .
            "📍 *Alamat :* {$payment->alamat_plg}\n" .
            "💵 *Jumlah :* Rp " . number_format($payment->jumlah_pembayaran, 0, ',', '.') . "\n" .
            "📅 *Tanggal :* {$payment->created_at}\n" .
            "💳 *Metode :* {$payment->metode_transaksi}\n" .
            "📝 *Untuk :* {$payment->untuk_pembayaran}\n" .
            "==============================\n" .
            "📊 *Total Tagihan :* Rp " . number_format($totalJumlahPembayaranKeseluruhan, 0, ',', '.') . " 🔹👥 {$totalPelangganKeseluruhan}\n" .
            "💰 *Sudah Dibayar :* Rp " . number_format($totalJumlahPaid, 0, ',', '.') . " 🔹👥 {$totalPelangganPaid}\n" .
            "💸 *Sisa :* Rp " . number_format($sisaPembayaran, 0, ',', '.') . " 🔹👥 {$sisaUser}\n" .
            "🙎🏻‍♂️ *Admin :* {$payment->admin_name}\n\n" .
            "✨ Semoga Berkah di Bulan Ramadan 🌟";

        $client = new Client();

        try {
            $client->post($url, [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Telegram Notification Error: " . $e->getMessage());
        }
    }


    public function bayar_mudah_hp_asli(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            'metode_transaksi' => 'required|string',
            'untuk_pembayaran' => 'required|string',
            'tanggal_pembayaran' => 'nullable|date_format:Y-m',
            'nm_pengirim' => 'required|string',
            'tgl_kirim' => 'required|string',


        ]);

        $pelanggan = Pelanggan::findOrFail($request->id);

        // Jangan ditagih kalau pelanggan baru daftar bulan ini
        $bulanIni = Carbon::now()->format('Y-m');
        $createdAt = Carbon::parse($pelanggan->created_at)->format('Y-m');

        if ($createdAt == $bulanIni) {
            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                ->with('alert', 'Pelanggan ' . $pelanggan->nama_plg . ' merupakan pelanggan baru bulan ini. Tagihan akan dimulai bulan depan.');
        }


        // Proses tanggal pembayaran
        if ($request->filled('tanggal_pembayaran')) {
            $tanggalPembayaran = $request->tanggal_pembayaran . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = $request->tanggal_pembayaran;
        } else {
            $tanggalPembayaran = Carbon::now()->format('Y-m') . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = Carbon::now()->format('Y-m');
        }

        // Cek apakah sudah bayar di bulan ini
        $existingPayment = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->where('tanggal_pembayaran', 'like', $bulanPembayaran . '%')
            ->exists();

        if ($existingPayment) {
            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                ->with('alert', 'Gagal!! Karena Pembayaran untuk bulan ini sudah dilakukan untuk Pelanggan ' . $pelanggan->nama_plg . '.');
        }

        $bulanPembayaranCarbon = Carbon::createFromFormat('Y-m', $bulanPembayaran)->startOfMonth();

        // Ambil pembayaran terakhir (jika mau tetap disimpan untuk keperluan lain)
        $pembayaranTerakhir = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tanggal_pembayaran', 'desc')
            ->first();

        // Tidak perlu cek tunggakan atau urutan bulan, langsung lanjut proses pembayaran


        // Cek apakah ada tunggakan bulan sebelumnya
        // $bulanPembayaranCarbon = Carbon::createFromFormat('Y-m', $bulanPembayaran)->startOfMonth();
        // $pembayaranTerakhir = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
        //    ->orderBy('tanggal_pembayaran', 'desc')
        //     ->first();

        //   if ($pembayaranTerakhir) {
        //       $lastPaidMonth = Carbon::parse($pembayaranTerakhir->tanggal_pembayaran)->startOfMonth();
        //      $expectedNextPaymentMonth = $lastPaidMonth->copy()->addMonth();

        //       if ($bulanPembayaranCarbon->gt($expectedNextPaymentMonth)) {
        //            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
        //               ->with('alert', 'Gagal!! Pelanggan belum bayar bulan sebelumnya. Harus bayar bulan '
        //                     . $expectedNextPaymentMonth->locale('id')->isoFormat('MMMM Y') . ' dulu.');
        //        }
        //    }

        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';


        $payment = BayarPelanggan::create([
            'pelanggan_id' => $pelanggan->id,
            'id_plg' => $pelanggan->id_plg ?? null,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'jumlah_pembayaran' => $pelanggan->harga_paket,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'metode_transaksi' => $request->metode_transaksi,
            'untuk_pembayaran' => $request->untuk_pembayaran,
            'keterangan_plg' => $request->keterangan_plg,
            'keterangan_plg' => $request->keterangan_plg,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'admin_name' => $adminName,
            'nm_pengirim' => $request->nm_pengirim,
            'tgl_kirim' => $request->tgl_kirim,
        ]);


        $pembayaranTerakhir = $pelanggan->pembayaranTerakhir; // Pastikan relasi ini tersedia

        // Cek apakah ada pembayaran terakhir
        if ($pembayaranTerakhir) {
            $bulanTerakhir = Carbon::parse($pembayaranTerakhir->tanggal)->format('Ym'); // Contoh: 202504
            $bulanSekarang = Carbon::now()->format('Ym');

            // Jika bulan terakhir >= bulan sekarang, maka paid, else isolir
            $pelanggan->status_pembayaran = ($bulanTerakhir >= $bulanSekarang) ? 'paid' : 'isolir';
        } else {
            // Kalau belum pernah bayar, langsung anggap isolir
            $pelanggan->status_pembayaran = 'isolir';
        }

        $pelanggan->save();

        // ✅ Log aktivitas pembayaran
        logActivity('Melakukan pembayaran pelanggan', 'Pembayaran', [
            'pelanggan_id' => $pelanggan->id,
            'nama' => $pelanggan->nama_plg,
            'jumlah' => $pelanggan->harga_paket,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'metode' => $request->metode_transaksi,
            'untuk' => $request->untuk_pembayaran,
        ]);

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($payment);

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil dilakukan untuk pelanggan ' . $pelanggan->nama_plg . '.');
    }

    public function bayar_mudah_hp_asli_backup_belum_bayar_bulan_sebelumnya(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            'metode_transaksi' => 'required|string',
            'untuk_pembayaran' => 'required|string',
            'tanggal_pembayaran' => 'nullable|date_format:Y-m',
            'nm_pengirim' => 'required|string',
            'tgl_kirim' => 'required|string',


        ]);

        $pelanggan = Pelanggan::findOrFail($request->id);

        // Jangan ditagih kalau pelanggan baru daftar bulan ini
        $bulanIni = Carbon::now()->format('Y-m');
        $createdAt = Carbon::parse($pelanggan->created_at)->format('Y-m');

        if ($createdAt == $bulanIni) {
            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                ->with('alert', 'Pelanggan ' . $pelanggan->nama_plg . ' merupakan pelanggan baru bulan ini. Tagihan akan dimulai bulan depan.');
        }


        // Proses tanggal pembayaran
        if ($request->filled('tanggal_pembayaran')) {
            $tanggalPembayaran = $request->tanggal_pembayaran . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = $request->tanggal_pembayaran;
        } else {
            $tanggalPembayaran = Carbon::now()->format('Y-m') . '-' . $pelanggan->tgl_tagih_plg;
            $bulanPembayaran = Carbon::now()->format('Y-m');
        }

        // Cek apakah sudah bayar di bulan ini
        $existingPayment = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->where('tanggal_pembayaran', 'like', $bulanPembayaran . '%')
            ->exists();

        if ($existingPayment) {
            return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                ->with('alert', 'Gagal!! Karena Pembayaran untuk bulan ini sudah dilakukan untuk Pelanggan ' . $pelanggan->nama_plg . '.');
        }

        // Cek apakah ada tunggakan bulan sebelumnya
        $bulanPembayaranCarbon = Carbon::createFromFormat('Y-m', $bulanPembayaran)->startOfMonth();
        $pembayaranTerakhir = BayarPelanggan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tanggal_pembayaran', 'desc')
            ->first();

        if ($pembayaranTerakhir) {
            $lastPaidMonth = Carbon::parse($pembayaranTerakhir->tanggal_pembayaran)->startOfMonth();
            $expectedNextPaymentMonth = $lastPaidMonth->copy()->addMonth();

            if ($bulanPembayaranCarbon->gt($expectedNextPaymentMonth)) {
                return redirect()->route('pembayaran_mudah.index', $pelanggan->id)
                    ->with('alert', 'Gagal!! Pelanggan belum bayar bulan sebelumnya. Harus bayar bulan '
                        . $expectedNextPaymentMonth->locale('id')->isoFormat('MMMM Y') . ' dulu.');
            }
        }

        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';


        $payment = BayarPelanggan::create([
            'pelanggan_id' => $pelanggan->id,
            'id_plg' => $pelanggan->id_plg ?? null,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'jumlah_pembayaran' => $pelanggan->harga_paket,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'metode_transaksi' => $request->metode_transaksi,
            'untuk_pembayaran' => $request->untuk_pembayaran,
            'keterangan_plg' => $request->keterangan_plg,
            'keterangan_plg' => $request->keterangan_plg,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'admin_name' => $adminName,
            'nm_pengirim' => $request->nm_pengirim,
            'tgl_kirim' => $request->tgl_kirim,
        ]);


        $pembayaranTerakhir = $pelanggan->pembayaranTerakhir; // Pastikan relasi ini tersedia

        // Cek apakah ada pembayaran terakhir
        if ($pembayaranTerakhir) {
            $bulanTerakhir = Carbon::parse($pembayaranTerakhir->tanggal)->format('Ym'); // Contoh: 202504
            $bulanSekarang = Carbon::now()->format('Ym');

            // Jika bulan terakhir >= bulan sekarang, maka paid, else isolir
            $pelanggan->status_pembayaran = ($bulanTerakhir >= $bulanSekarang) ? 'paid' : 'isolir';
        } else {
            // Kalau belum pernah bayar, langsung anggap isolir
            $pelanggan->status_pembayaran = 'isolir';
        }

        $pelanggan->save();

        // ✅ Log aktivitas pembayaran
        logActivity('Melakukan pembayaran pelanggan', 'Pembayaran', [
            'pelanggan_id' => $pelanggan->id,
            'nama' => $pelanggan->nama_plg,
            'jumlah' => $pelanggan->harga_paket,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'metode' => $request->metode_transaksi,
            'untuk' => $request->untuk_pembayaran,
        ]);

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($payment);

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil dilakukan untuk pelanggan ' . $pelanggan->nama_plg . '.');
    }



    public function aktifkanReactivasi(Request $request)
    {


        // Ambil data pelanggan berdasarkan id
        $pelanggan = Pelanggan::findOrFail($request->id);

        // Update status pembayaran pelanggan menjadi 'paid'
        $pelanggan->status_pembayaran = 'unpaid';
        $pelanggan->save();

        // Redirect ke halaman history pembayaran dengan pesan sukses
        return redirect()->route('pelanggan.reactivasi', $pelanggan->id)
            ->with('success', 'Pelanggann Reactivasi berhasil diaktifkan ' . $pelanggan->nama_plg . '.');
    }


    public function historypembayaran($id_plg)
    {
        // Mencari pelanggan di tabel Pelanggan
        $pelanggan = Pelanggan::find($id_plg);

        // Jika tidak ditemukan, cari di tabel IsolirModel
        if (!$pelanggan) {
            //$pelanggan = IsolirModel::where('id_plg', $id_plg)->first();
            $pelanggan = Pelangganof::where('id_plg', $id_plg)->first();
        }

        // Jika pelanggan tidak ditemukan di kedua tabel
        if (!$pelanggan) {
            return redirect()->route('pelanggan.index')->with('error', 'Pelanggan tidak ditemukan.');
        }

        // Ambil riwayat pembayaran berdasarkan id_plg yang konsisten
        $pembayaran = BayarPelanggan::where('id_plg', $pelanggan->id_plg)->get();

        return view('pelanggan.historypembayaran', compact('pelanggan', 'pembayaran'));
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

    //UPDATE STATUS INDEX
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:unpaid,paid',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        // Gunakan strcasecmp untuk membandingkan tanpa memperhatikan huruf kapital
        if (strcasecmp($request->status_pembayaran, 'unpaid') === 0) {
            $pelanggan->status_pembayaran = 'unpaid';
        } elseif (strcasecmp($request->status_pembayaran, 'paid') === 0) {
            $pelanggan->status_pembayaran = 'paid';
        }

        $pelanggan->save();

        return redirect()->route('pelanggan.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function toIsolir1($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Pindahkan data ke tabel isolir dengan pengecekan untuk kolom nullable
        IsolirModel::create([
            'id_plg' => $pelanggan->id_plg,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'no_telepon_plg' => $pelanggan->no_telepon_plg ?? null,
            'aktivasi_plg' => $pelanggan->aktivasi_plg ?? null,
            'paket_plg' => $pelanggan->paket_plg ?? null,
            'harga_paket' => $pelanggan->harga_paket ?? null,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg ?? null, // Beri nilai null jika tidak diisi
            'keterangan_plg' => $pelanggan->keterangan_plg ?? '-', // Contoh nilai default jika kosong
            'odp' => $pelanggan->odp ?? null, // Beri nilai null jika tidak diisi
            'longitude' => $pelanggan->longitude ?? null, // Beri nilai null jika tidak diisi
            'latitude' => $pelanggan->latitude ?? null, // Beri nilai null jika tidak diisi
            'status_pembayaran' => $pelanggan->status_pembayaran ?? 'unpaid', // Contoh nilai default
        ]);

        // Hapus dari tabel pelanggan
        $pelanggan->delete();

        return redirect()->route('isolir.index')->with('success', 'Pelanggan berhasil dimasukan ke Isolir.');
    }

    public function filterByTanggalTagih(Request $request)
    {
        // Ambil nilai filter status pembayaran dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');

        // Ambil tanggal tagih dan paket dari request
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');

        // Mulai query
        $query = Pelanggan::query();

        // Filter berdasarkan status pembayaran jika ada
        if ($status_pembayaran_display) {
            $query->where('status_pembayaran', $status_pembayaran_display);
        }

        // Filter berdasarkan tanggal tagih jika ada
        if ($tanggal) {
            $query->where('tgl_tagih_plg', $tanggal);
        }

        // Filter berdasarkan paket pelanggan jika ada
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Lakukan pagination pada query
        $pelanggan = $query->paginate(100);

        // Kembalikan data pelanggan ke view
        return view('pelanggan.index_tagih', compact('pelanggan', 'paket_plg', 'tanggal', 'status_pembayaran_display'));
    }


    public function filterByTanggalTagihindex(Request $request)
    {
        // Ambil nilai filter dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal_tagih = $request->input('tgl_tagih_plg', null);
        $paket_plg = $request->input('paket_plg', null);
        $harga_paket = $request->input('harga_paket', null);
        $filter = $request->input('filter', null);
        $tanggal_pembayaran = $request->input('tanggal', null);

        // Mulai query dasar pada model Pelanggan
        $query = Pelanggan::query();

        // Filter berdasarkan status pembayaran
        if ($status_pembayaran_display) {
            $query->whereRaw('LOWER(status_pembayaran) = ?', [strtolower($status_pembayaran_display)]);
        }

        // Filter berdasarkan tanggal tagih
        if ($tanggal_tagih) {
            $query->where('tgl_tagih_plg', $tanggal_tagih);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        // Filter status pembayaran
        if ($filter) {
            if ($filter == 'paid') {
                $query->whereNotNull('pembayaranTerakhir');
            } elseif ($filter == 'unpaid') {
                $query->whereNull('pembayaranTerakhir');
            }
        }

        // Urutkan berdasarkan pembayaran terbaru atau terlama
        if ($filter == 'terbaru') {
            $query->orderBy('pembayaranTerakhir', 'desc');
        } elseif ($filter == 'terlama') {
            $query->orderBy('pembayaranTerakhir', 'asc');
        }

        // Filter berdasarkan tanggal pembayaran
        if ($tanggal_pembayaran) {
            $query->whereDate('pembayaranTerakhir', '=', $tanggal_pembayaran);
        }

        // Ambil data yang difilter
        $items = $query->get();

        // Hitung total harga_paket dan jumlah pelanggan berdasarkan filter
        $totalJumlahPembayaranfilter = $query->sum('harga_paket'); // Total pendapatan dari pelanggan yang difilter

        // Hitung total pelanggan berdasarkan filter
        $totalPelangganfilter = $query->count();

        // Hitung total jumlah pembayaran keseluruhan (tanpa filter)
        $totalJumlahPembayaranKeseluruhan = Pelanggan::sum('harga_paket'); // Total keseluruhan dari tabel Pelanggan
        $totalPelangganKeseluruhan = Pelanggan::count(); // Total pelanggan keseluruhan

        // Hitung total pembayaran dalam bulan ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('jumlah_pembayaran');

        // Hitung total pelanggan yang sudah membayar dalam bulan ini
        $totalPelangganBayar = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('id_plg')
            ->count('id_plg');

        // Hitung sisa pembayaran dan sisa pelanggan yang belum membayar
        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Pagination
        $pelanggan = $query->paginate(100)->appends($request->all());

        // Kembalikan data ke view dengan total jumlah pembayaran dan pelanggan
        return view('pelanggan.index', compact(
            'totalJumlahPembayaran',
            'totalJumlahPembayaranfilter',
            'sisaPembayaran',
            'sisaUser',
            'totalPelangganKeseluruhan',
            'totalPelangganfilter',
            'totalPelangganBayar',
            'totalJumlahPembayaranKeseluruhan',
            'pelanggan',
            'harga_paket',
            'paket_plg',
            'tanggal_tagih',
            'status_pembayaran_display',
            'tanggal_pembayaran',
            'items',
        ));
    }

    public function updatePaymentStatus()
    {
        // Ambil semua pelanggan yang belum berstatus Isolir atau off
        $pelanggans = Pelanggan::whereNotIn('status_pembayaran', ['Isolir', 'off'])->get();

        foreach ($pelanggans as $pelanggan) {
            $statusLama = $pelanggan->status_pembayaran; // Simpan status awal

            // Skip jika status sudah Isolir (harusnya tidak perlu karena query di atas)
            if ($statusLama === 'Isolir') {
                continue;
            }

            // Tangani pelanggan dengan status PSB atau Reactivasi
            if (in_array($statusLama, ['PSB', 'Reactivasi'])) {
                if (Carbon::now()->day !== 1) {
                    continue; // Lewati jika belum tanggal 1
                } else {
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            }

            // Ambil pembayaran terakhir
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->first();

            $createdAtPembayaran = $pembayaranTerakhir
                ? Carbon::parse($pembayaranTerakhir->tanggal_pembayaran)
                : null;

            // Ambil tanggal tagihan terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray);

            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                // Format tanggal tagihan
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // Jika pembayaran bulan depan/sudah dibayar, tetap paid
                if ($createdAtPembayaran && ($createdAtPembayaran->year > $currentYear ||
                    ($createdAtPembayaran->year == $currentYear && $createdAtPembayaran->month > $currentMonth))) {
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                    continue;
                }

                // Jika sudah bayar bulan ini
                if (
                    $createdAtPembayaran &&
                    $createdAtPembayaran->month == $currentMonth &&
                    $createdAtPembayaran->year == $currentYear
                ) {
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                    continue;
                }

                // Jika sudah melewati tanggal tagihan, isolir
                if (Carbon::now()->greaterThan($tglTagihPlg) && !Carbon::now()->isSameDay($tglTagihPlg)) {
                    $pelanggan->status_pembayaran = 'Isolir';

                    // Simpan tgl_isolir hanya jika belum pernah diisolir sebelumnya
                    if ($statusLama !== 'Isolir') {
                        $pelanggan->tgl_isolir = Carbon::now()->format('Y-m-d H:i:s');
                    }
                } else {
                    // Belum lewat tagihan, tetap unpaid
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            } else {
                // Jika tanggal tagihan tidak valid
                $pelanggan->status_pembayaran = 'unpaid';
            }

            // Simpan perubahan
            $pelanggan->save();
        }

        return redirect()->route('pelanggan.index')->with('success', 'Status Pembayaran Pelanggan telah diperbarui.');
    }



    public function updatePaymentStatus2()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Jika status sudah "Isolir", lewati pelanggan ini tanpa mengubah status
            if ($pelanggan->status_pembayaran === 'Isolir') {
                continue;
            }

            // Tambahkan logika untuk pelanggan dengan status "PSB" atau "Reaktivasi"
            if (in_array($pelanggan->status_pembayaran, ['PSB', 'Reactivasi'])) {
                // Jika hari ini belum tanggal 1, abaikan perubahan status
                if (Carbon::now()->day !== 1) {
                    continue;
                } else {
                    // Jika sudah tanggal 1, ubah status menjadi "unpaid"
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            }

            // Ambil pembayaran terakhir dari tabel BayarPelanggan berdasarkan id_plg
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->first();

            // Ambil tanggal pembayaran terakhir jika ada
            $createdAtPembayaran = $pembayaranTerakhir ? Carbon::parse($pembayaranTerakhir->tanggal_pembayaran) : null;

            // Ambil tanggal tagihan terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray); // Ambil tanggal terakhir dari array

            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                // Buat tanggal tagihan lengkap dengan format Y-m-d
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // Cek apakah pembayaran terakhir ada dan lebih dari bulan sekarang
                if ($createdAtPembayaran && ($createdAtPembayaran->year > $currentYear ||
                    ($createdAtPembayaran->year == $currentYear && $createdAtPembayaran->month > $currentMonth))) {
                    // Jika pelanggan sudah membayar untuk bulan mendatang, status tetap "paid"
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                    continue; // Stop pemrosesan lebih lanjut untuk pelanggan ini
                }

                // Jika ada pembayaran bulan ini, status tetap "paid"
                if (
                    $createdAtPembayaran && $createdAtPembayaran->month === Carbon::now()->month &&
                    $createdAtPembayaran->year === Carbon::now()->year
                ) {
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                    continue; // Stop pemrosesan lebih lanjut untuk pelanggan ini
                }

                // Jika sudah melewati tanggal tagihan dan status bukan "paid", ubah menjadi "Isolir"
                if (Carbon::now()->greaterThan($tglTagihPlg) && !Carbon::now()->isSameDay($tglTagihPlg)) {
                    $pelanggan->status_pembayaran = 'Isolir';
                } else {
                    // Jika belum melewati tanggal tagihan atau tepat di tanggal tagihan, status tetap "unpaid"
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            } else {
                // Jika tanggal tagihan tidak valid, set status default "unpaid"
                $pelanggan->status_pembayaran = 'unpaid';
            }

            // Simpan perubahan status pelanggan
            $pelanggan->save();
        }

        return redirect()->route('pelanggan.index')->with('success', 'Status Pembayaran Pelanggan telah diperbarui.');
    }


    public function updatePaymentStatus_kurang_reactivasi()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Jika status sudah "Isolir", lewati pelanggan ini tanpa mengubah status
            if ($pelanggan->status_pembayaran === 'Isolir') {
                continue;
            }

            // Tambahkan logika untuk pelanggan dengan status "PSB"
            if ($pelanggan->status_pembayaran === 'PSB') {
                // Jika hari ini belum tanggal 1, abaikan perubahan status
                if (Carbon::now()->day !== 1) {
                    continue;
                } else {
                    // Jika sudah tanggal 1, ubah status menjadi "unpaid"
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            }

            // Ambil pembayaran terakhir dari tabel BayarPelanggan berdasarkan id_plg
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->first();

            // Ambil tanggal pembayaran terakhir jika ada
            $createdAtPembayaran = $pembayaranTerakhir ? \Carbon\Carbon::parse($pembayaranTerakhir->tanggal_pembayaran) : null;

            // Ambil tanggal tagihan terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray); // Ambil tanggal terakhir dari array

            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                // Buat tanggal tagihan lengkap dengan format Y-m-d
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // Logika status pembayaran berdasarkan pembayaran terakhir dan tanggal tagihan
                if ($createdAtPembayaran && $createdAtPembayaran->month === Carbon::now()->month && $createdAtPembayaran->year === Carbon::now()->year) {
                    // Jika ada pembayaran bulan ini, status tetap "paid"
                    $pelanggan->status_pembayaran = 'paid';
                } elseif (Carbon::now()->greaterThan($tglTagihPlg) && !Carbon::now()->isSameDay($tglTagihPlg)) {
                    // Jika sudah melewati tanggal tagihan (lebih dari 1 hari) dan unpaid, ubah status menjadi "Isolir"
                    $pelanggan->status_pembayaran = 'Isolir';
                } else {
                    // Jika belum melewati tanggal tagihan atau tepat di tanggal tagihan, status tetap "unpaid"
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            } else {
                // Jika tanggal tagihan tidak valid, set status default "unpaid"
                $pelanggan->status_pembayaran = 'unpaid';
            }

            // Simpan perubahan status pelanggan
            $pelanggan->save();
        }

        return redirect()->route('pelanggan.index')->with('success', 'Status Pembayaran Pelanggan telah diperbarui.');
    }



    //ini yang bener
    public function updatePaymentStatus_kurang_PSB()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Jika status sudah "Isolir", lewati pelanggan ini tanpa mengubah status
            if ($pelanggan->status_pembayaran === 'Isolir') {
                continue;
            }

            // Ambil pembayaran terakhir dari tabel BayarPelanggan berdasarkan id_plg
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->first();

            // Ambil tanggal pembayaran terakhir jika ada
            $createdAtPembayaran = $pembayaranTerakhir ? \Carbon\Carbon::parse($pembayaranTerakhir->tanggal_pembayaran) : null;

            // Ambil tanggal tagihan terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray); // Ambil tanggal terakhir dari array

            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                // Buat tanggal tagihan lengkap dengan format Y-m-d
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // Logika status pembayaran berdasarkan pembayaran terakhir dan tanggal tagihan
                if ($createdAtPembayaran && $createdAtPembayaran->month === Carbon::now()->month && $createdAtPembayaran->year === Carbon::now()->year) {
                    // Jika ada pembayaran bulan ini, status tetap "paid"
                    $pelanggan->status_pembayaran = 'paid';
                } elseif (Carbon::now()->greaterThan($tglTagihPlg) && !Carbon::now()->isSameDay($tglTagihPlg)) {
                    // Jika sudah melewati tanggal tagihan (lebih dari 1 hari) dan unpaid, ubah status menjadi "Isolir"
                    $pelanggan->status_pembayaran = 'Isolir';
                } else {
                    // Jika belum melewati tanggal tagihan atau tepat di tanggal tagihan, status tetap "unpaid"
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            } else {
                // Jika tanggal tagihan tidak valid, set status default "unpaid"
                $pelanggan->status_pembayaran = 'unpaid';
            }

            // Simpan perubahan status pelanggan
            $pelanggan->save();
        }

        return redirect()->route('pelanggan.index')->with('success', 'Status Pembayaran Pelanggan telah diperbarui.');
    }



    public function updatePaymentStatus1()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Cek apakah status pelanggan adalah "Block" atau "Unblock"
            if (in_array($pelanggan->status_pembayaran, ['Block', 'Unblock', 'PSB', 'Request Pasang'])) {
                // Jika status "Block" atau "Unblock", lewati pelanggan ini (tidak berubah status)
                continue;
            }

            // Ambil pembayaran terakhir dari tabel BayarPelanggan berdasarkan id_plg
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->first();

            // Jika ada pembayaran terakhir, ambil tanggalnya
            if ($pembayaranTerakhir) {
                $createdAt = \Carbon\Carbon::parse($pembayaranTerakhir->tanggal_pembayaran);
            } else {
                // Jika belum ada pembayaran, anggap belum ada pembayaran (default ke null)
                $createdAt = null;
            }

            // Pisahkan tanggal tagihan menjadi array dan ambil tanggal terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray); // Ambil tanggal terakhir dari array

            // Cek apakah $tglTagihTerakhir adalah angka dan valid
            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year; // Ambil tahun saat ini
                $currentMonth = Carbon::now()->month; // Ambil bulan saat ini

                // Buat tanggal lengkap dengan format 'Y-m-d' (contohnya: '2024-09-25')
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // Cek apakah pelanggan sudah membayar pada bulan ini atau bulan depan
                if ($createdAt && ($createdAt->month == Carbon::now()->month || $createdAt->month == Carbon::now()->addMonth()->month) && $createdAt->year == Carbon::now()->year) {
                    // Jika paid pada bulan ini atau bulan depan, set status menjadi "paid"
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                } else {
                    // Jika unpaid dan tanggal sekarang sama dengan tgl_tagih_plg
                    if (Carbon::now()->isSameDay($tglTagihPlg)) {
                        // Ubah status menjadi "unpaid" jika tanggal saat ini sama dengan tgl_tagih_plg
                        $pelanggan->status_pembayaran = 'unpaid';
                        $pelanggan->save();
                    } elseif (Carbon::now()->greaterThan($tglTagihPlg)) {
                        // Jika sudah lewat jatuh tempo, ubah status menjadi "Isolir"
                        $pelanggan->status_pembayaran = 'Isolir';
                        $pelanggan->save();
                    } elseif (Carbon::now()->lessThan($tglTagihPlg)) {
                        // Jika tanggal saat ini kurang dari tgl_tagih_plg, status menjadi "paid"
                        $pelanggan->status_pembayaran = 'unpaid';
                        $pelanggan->save();
                    }
                }
            }
        }

        return redirect()->route('pelanggan.index')->with('success', 'Status Pembayaran Pelanggan telah diperbarui.');
    }




    public function updatePaymentStatusIsolir()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Ambil pembayaran terakhir dari tabel BayarPelanggan berdasarkan id_plg
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->first();

            // Jika ada pembayaran terakhir, ambil tanggalnya
            if ($pembayaranTerakhir) {
                $createdAt = \Carbon\Carbon::parse($pembayaranTerakhir->tanggal_pembayaran);
            } else {
                // Jika belum ada pembayaran, anggap belum ada pembayaran (default ke null)
                $createdAt = null;
            }

            // Pisahkan tanggal tagihan menjadi array dan ambil tanggal terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray); // Ambil tanggal terakhir dari array

            // Cek apakah $tglTagihTerakhir adalah angka dan valid
            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year; // Ambil tahun saat ini
                $currentMonth = Carbon::now()->month; // Ambil bulan saat ini

                // Buat tanggal lengkap dengan format 'Y-m-d' (contohnya: '2024-09-25')
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // Hitung H-5 dari tgl_tagih_plg
                $tanggalH5 = $tglTagihPlg->subDays(0);

                // Cek apakah pelanggan sudah membayar pada bulan ini atau bulan depan
                if ($createdAt && ($createdAt->month == Carbon::now()->month || $createdAt->month == Carbon::now()->addMonth()->month) && $createdAt->year == Carbon::now()->year) {
                    // Jika paid pada bulan ini atau bulan depan, tetap set status menjadi "paid"
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                } else {
                    // Jika unpaid bulan ini dan sudah H-5 dari tanggal tagih
                    if (Carbon::now()->greaterThanOrEqualTo($tanggalH5)) {
                        // Ubah status menjadi "unpaid"
                        $pelanggan->status_pembayaran = 'unpaid';
                        $pelanggan->save();
                    }
                }

                // Cek jika tgl_tagih sudah lewat dan status pembayaran "unpaid", ubah status jadi "Isolir"
                if (Carbon::now()->gt($tglTagihPlg) && $pelanggan->status_pembayaran === 'unpaid') {
                    $pelanggan->status_pembayaran = 'Isolir';
                    $pelanggan->save();
                }
            }
        }

        return redirect()->route('pelanggan.index')->with('success', 'Status Pembayaran Pelanggan telah diperbarui ke Isolir');
    }




    public function export1(Request $request, $format)
    {
        // Ambil filter dari request
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $status_pembayaran = $request->input('status_pembayaran');

        // Filter data berdasarkan input
        $pelanggan = Pelanggan::query()
            ->when($tgl_tagih_plg, function ($query) use ($tgl_tagih_plg) {
                return $query->where('tgl_tagih_plg', $tgl_tagih_plg);
            })
            ->when($paket_plg, function ($query) use ($paket_plg) {
                return $query->where('paket_plg', 'like', "%$paket_plg%");
            })
            ->when($harga_paket, function ($query) use ($harga_paket) {
                return $query->where('harga_paket', '>=', $harga_paket);
            })
            ->when($status_pembayaran, function ($query) use ($status_pembayaran) {
                return $query->where('status_pembayaran', $status_pembayaran);
            })
            ->get();

        // Ekspor PDF atau Excel
        if ($format === 'pdf') {
            $pdf = PDF::loadView('pelanggan.pdf', ['pelanggan' => $pelanggan]);
            return $pdf->download('bayar_pelanggan_' . now()->format('Y-m-d') . '.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new PelangganExport($pelanggan), 'bayar_pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }

        return redirect()->back()->with('error', 'Format tidak dikenali.');
    }

    public function offkan($id)
    {
        // Ambil data pelanggan dari tabel pelangganof
        $pelanggan = Pelanggan::find($id);

        if ($pelanggan) {
            try {
                // Pastikan tanggal tagih diubah ke format yang sesuai jika perlu
                // $tglTagih = Carbon::createFromFormat('d/m/Y', $pelanggan->aktivasi_plg)->format('Y-m-d');

                // Masukkan data ke tabel pelanggan
                DB::table('plg_off')->insert([
                    'id_plg' => $pelanggan->id_plg,
                    'nama_plg' => $pelanggan->nama_plg,
                    'alamat_plg' => $pelanggan->alamat_plg,
                    'no_telepon_plg' => $pelanggan->no_telepon_plg,
                    'aktivasi_plg' => $pelanggan->aktivasi_plg,  // Pastikan format tanggal sesuai
                    'paket_plg' => $pelanggan->paket_plg,
                    'harga_paket' => $pelanggan->harga_paket,
                    'odp' => $pelanggan->odp,
                    'keterangan_plg' => $pelanggan->keterangan_plg,
                    'longitude' => $pelanggan->longitude,
                    'latitude' => $pelanggan->latitude,
                    'tgl_tagih_plg' => $pelanggan->tglTagih,  // Menambahkan kolom yang hilang
                    'created_at' => now(),
                    'updated_at' => now(),



                ]);
                $pelanggan->status_pembayaran = 'OFF';

                // Hapus data dari tabel pelanggan
                // $pelanggan->delete();

                // Redirect ke halaman pelanggan dengan pesan sukses
                return redirect()->route('pelanggan.isolir')->with('success', 'Pelanggan OFF berhasil di Aktifkan Kembali.');
            } catch (\Exception $e) {
                Log::error('Error off: ' . $e->getMessage());
                dd($e);  // Menampilkan detail error untuk debugging
                return redirect()->route('pelanggan.isolir')->with('error', 'Terjadi kesalahan saat memindahkan pelanggan.');
            }
        } else {
            return redirect()->route('pelanggan.isolir')->with('error', 'Pelanggan tidak ditemukan.');
        }
    }


    public function export(Request $request, $format)
    {
        // Ambil filter dari request
        $tgl_tagih_plg = $request->input('tgl_tagih_plg'); // Bisa berupa array dari checkbox
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $status_pembayaran = $request->input('status_pembayaran');

        // Query awal
        //$query = Pelanggan::query();
        $query = Pelanggan::whereNotIn('paket_plg', ['vcr']);

        // Tambahkan filter hanya jika parameter diisi
        if (!empty($tgl_tagih_plg)) {
            if (is_array($tgl_tagih_plg)) {
                $query->whereIn('tgl_tagih_plg', $tgl_tagih_plg); // Jika checkbox banyak dipilih
            } else {
                $query->where('tgl_tagih_plg', $tgl_tagih_plg); // Jika hanya 1 pilihan
            }
        }

        // Tambahkan sorting berdasarkan tanggal tagih terkecil ke terbesar
        $query->orderByRaw("CAST(tgl_tagih_plg AS UNSIGNED) ASC");


        if (!empty($paket_plg)) {
            $query->where('paket_plg', $paket_plg);
        }

        if (!empty($harga_paket)) {



            $query->where('harga_paket', $harga_paket);
        }

        if (!empty($status_pembayaran) && in_array($status_pembayaran, ['unpaid', 'paid', 'Isolir'])) {
            $query->where('status_pembayaran', $status_pembayaran);
        }

        // Ambil data sesuai filter
        $pelanggan = $query->get();

        // Ekspor data ke format yang diminta
        if ($format === 'pdf') {
            $pdf = PDF::loadView('pelanggan.pdf', ['pelanggan' => $pelanggan]);
            return $pdf->download('pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PelangganExport($pelanggan), 'pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }



    public function export11(Request $request, $format)
    {
        // Ambil filter dari request
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $status_pembayaran = $request->input('status_pembayaran');

        // Query awal
        $query = Pelanggan::whereIn('status_pembayaran', ['paid', 'unpaid']);

        // Tambahkan filter
        $query = $query->when($tgl_tagih_plg, function ($query) use ($tgl_tagih_plg) {
            return $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        })
            ->when($paket_plg, function ($query) use ($paket_plg) {
                return $query->where('paket_plg', $paket_plg);
            })
            ->when($status_pembayaran, function ($query) use ($status_pembayaran) {
                return $query->where('status_pembayaran', $status_pembayaran);
            })
            ->when($harga_paket, function ($query) use ($harga_paket) {
                return $query->where('harga_paket', $harga_paket);
            });

        // Ambil data sesuai filter
        $pelanggan = $query->get();

        // Ekspor data
        if ($format === 'pdf') {
            $pdf = PDF::loadView('pelanggan.pdf', ['pelanggan' => $pelanggan]);
            return $pdf->download('pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PelangganExport($pelanggan), 'pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }



    public function export2(Request $request, $format)
    {
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $status_pembayaran = $request->input('status_pembayaran');

        // Membuat query awal untuk pelanggan dengan status tertentu
        $query = Pelanggan::whereIn('status_pembayaran', ['paid', 'unpaid']);

        // Menambahkan filter berdasarkan input yang diterima
        $query = $query->when($tgl_tagih_plg, function ($query) use ($tgl_tagih_plg) {
            return $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        })
            ->when($paket_plg, function ($query) use ($paket_plg) {
                return $query->where('paket_plg', $paket_plg);
            })
            ->when($harga_paket, function ($query) use ($harga_paket) {
                return $query->where('harga_paket', $harga_paket);
            })
            ->when($status_pembayaran, function ($query) use ($status_pembayaran) {
                return $query->where('status_pembayaran', $status_pembayaran);
            });

        // Mendapatkan hasil query
        $pelanggan = $query->get();

        // Menghasilkan file sesuai dengan format yang diminta
        if ($format === 'pdf') {
            $pdf = PDF::loadView('pelanggan.pdf', ['pelanggan' => $pelanggan]);
            return $pdf->download('bayar_pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PelangganController($pelanggan), 'bayar_pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }


    public function export_isolir(Request $request, $format)
    {
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $status_pembayaran = $request->input('status_pembayaran');

        // Membuat query awal untuk pelanggan dengan status tertentu
        $query = Pelanggan::whereIn('status_pembayaran', ['Isolir', 'Block', 'Unblockk']);

        // Menambahkan filter berdasarkan input yang diterima
        $query = $query->when($tgl_tagih_plg, function ($query) use ($tgl_tagih_plg) {
            return $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        })
            ->when($paket_plg, function ($query) use ($paket_plg) {
                return $query->where('paket_plg', $paket_plg);
            })
            ->when($harga_paket, function ($query) use ($harga_paket) {
                return $query->where('harga_paket', $harga_paket);
            })
            ->when($status_pembayaran, function ($query) use ($status_pembayaran) {
                return $query->where('status_pembayaran', $status_pembayaran);
            });

        // Mendapatkan hasil query
        $pelanggan = $query->get();

        // Menghasilkan file sesuai dengan format yang diminta
        if ($format === 'pdf') {
            $pdf = PDF::loadView('pelanggan.pdf', ['pelanggan' => $pelanggan]);
            return $pdf->download('bayar_pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PelangganController($pelanggan), 'bayar_pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }


    public function index_psb(Request $request)
    {
        // Ambil filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $search = $request->input('search');

        // Query dasar untuk pelanggan status 'off' dan 'Request Pasang'
        $queryFilterable = Pelanggan::whereIn('status_pembayaran', ['PSB', 'Request Pasang']);

        // Terapkan filter
        if ($paket_plg) $queryFilterable->where('paket_plg', $paket_plg);
        if ($harga_paket) $queryFilterable->where('harga_paket', $harga_paket);
        if ($tgl_tagih_plg) $queryFilterable->where('tgl_tagih_plg', $tgl_tagih_plg);
        if ($created_at) $queryFilterable->whereDate('created_at', $created_at);

        if ($jumlah_pembayaran) {
            $queryFilterable->whereHas('pembayaran', function ($q) use ($jumlah_pembayaran) {
                $q->where('jumlah_pembayaran', '>=', $jumlah_pembayaran);
            });
        }

        if ($search) {
            $queryFilterable->where(function ($q) use ($search) {
                $q->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%");
            });
        }

        // Data utama untuk ditampilkan (pagination)
        $pelanggan = $queryFilterable->with(['pembayaran', 'pembayaranTerakhir'])->paginate(100);

        // Hitung total berdasarkan filter
        $totalJumlahPembayaranfilter = $queryFilterable->sum('harga_paket');
        $totalPelangganfilter = $queryFilterable->count();

        // Clone query untuk status tertentu
        $totalSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->count();
        $totalBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->count();
        $totalIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->count();

        $totalPembayaranSudahBayar = (clone $queryFilterable)->where('status_pembayaran', 'paid')->sum('harga_paket');
        $totalPembayaranBelumBayar = (clone $queryFilterable)->where('status_pembayaran', 'unpaid')->sum('harga_paket');
        $totalPembayaranIsolir     = (clone $queryFilterable)->where('status_pembayaran', 'Isolir')->sum('harga_paket');

        $totalSisa_Uang = $totalPembayaranBelumBayar + $totalPembayaranIsolir;
        $totalSisa_User = $totalBelumBayar + $totalIsolir;

        // Perhitungan keseluruhan tanpa filter
        $queryKeseluruhan = Pelanggan::whereIn('status_pembayaran', ['off', 'Request Pasang']);
        $totalJumlahPembayaranKeseluruhan = $queryKeseluruhan->sum('harga_paket');
        $totalPelangganKeseluruhan = $queryKeseluruhan->count();

        // Pembayaran masuk bulan ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('jumlah_pembayaran');

        $userIdsWithPayments = BayarPelanggan::whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->distinct('id_plg')
            ->pluck('id_plg');

        $totalPelangganBayar = $userIdsWithPayments->count();
        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Pembayaran berdasarkan tanggal filter
        $totalJumlahPembayaranMasuk = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->sum('jumlah_pembayaran') : 0;

        $totalPelangganBayarFiltered = $tgl_tagih_plg ?
            BayarPelanggan::whereDate('created_at', $tgl_tagih_plg)->distinct('id_plg')->count('id_plg') : 0;

        return view('pelanggan.psb', compact(
            'pelanggan',
            'totalJumlahPembayaranfilter',
            'totalPelangganfilter',
            'totalSudahBayar',
            'totalPembayaranSudahBayar',
            'totalBelumBayar',
            'totalPembayaranBelumBayar',
            'totalIsolir',
            'totalPembayaranIsolir',
            'totalSisa_Uang',
            'totalSisa_User',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalJumlahPembayaran',
            'totalPelangganBayar',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranMasuk',
            'totalPelangganBayarFiltered',
            'search'
        ));
    }

    public function updateODP(Request $request, $id)
    {
        $request->validate([
            'odp' => 'required|string|max:255',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->odp = $request->odp;
        $pelanggan->save();

        return redirect()->back()->with('success', 'ODP berhasil diperbarui.');
    }

    public function ubahStatusOff($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->status_pembayaran = 'off';
        $pelanggan->tgl_off = Carbon::now(); // Menyimpan tanggal perubahan
        $pelanggan->save();

        return redirect()->back()->with('success', 'Status pembayaran berhasil diubah menjadi OFF.');
    }

    public function ubahStatusOn($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->status_pembayaran = 'unpaid';
        $pelanggan->tgl_on = Carbon::now(); // Menyimpan tanggal perubahan
        $pelanggan->save();

        return redirect()->back()->with('success', 'Status pembayaran berhasil diubah menjadi On.');
    }


    public function aktifkanPSB(Request $request)
    {

        $pelanggan = Pelanggan::findOrFail($request->id);

        $pelanggan->status_pembayaran = 'unpaid';
        $pelanggan->save();

        return redirect()->route('pelanggan.psb', $pelanggan->id)
            ->with('success', 'Pelanggann Baru Pasang berhasil Diaktifkan ' . $pelanggan->nama_plg . '.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Perbaikan::query();

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Pencarian berdasarkan ID pelanggan atau nama pelanggan
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id_plg', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_plg', 'like', '%' . $request->search . '%');
            });
        }

        $pelanggan = Pelanggan::all();

        $query_cari = Pelanggan::all();

        $query_cari = $request->input('q'); // Input dari pencarian


        if ($query_cari) {
            $pelanggan = Pelanggan::with('pembayaran')
                ->where('id_plg', $query_cari)
                ->orWhere('nama_plg', 'LIKE', "%$query_cari%")
                ->paginate(200);
        }

        // Ambil nilai filter status pembayaran dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $tanggal_pembayaran = $request->input('tanggal_pembayaran');
        $created_at = $request->input('created_at');
        $bulan = $request->input('bulan'); // Ambil bulan dari request
        $date_start = $request->input('date_start'); // Ambil tanggal mulai dari request
        $date_end = $request->input('date_end'); // Ambil tanggal akhir dari request
        $search = $request->input('search'); // Ambil input pencarian dari request
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        // Mulai query


        // Filter berdasarkan status pembayaran jika ada
        if ($status_pembayaran_display) {
            $query_cari->where('status_pembayaran', $status_pembayaran_display);
        }

        // Filter berdasarkan tanggal tagih jika ada
        if ($tanggal) {
            $query_cari->where('tgl_tagih_plg', $tanggal);
        }

        // Filter berdasarkan paket pelanggan jika ada
        if ($paket_plg) {
            $query_cari->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket jika ada
        if ($jumlah_pembayaran) {
            $query_cari->where('jumlah_pembayaran', $jumlah_pembayaran);
        }

        // Filter berdasarkan tanggal pembayaran (format Y-m-d) jika ada
        if ($created_at) {
            $query_cari->whereDate('created_at', $created_at);
        }

        // Filter berdasarkan bulan jika ada
        if ($bulan) {
            $query_cari->whereMonth('tanggal_pembayaran', $bulan);
        }

        // Filter berdasarkan tanggal mulai dan tanggal akhir jika ada
        if ($date_start && $date_end) {
            $query_cari->whereBetween('created_at', [$date_start, $date_end]);
        }

        // Filter berdasarkan pencarian jika ada
        if ($search) {
            $query_cari->where(function ($query_cari) use ($search) {
                $query_cari->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('metode_transaksi', 'like', "%{$search}%");
            });
        }

        if ($untuk_pembayaran) {
            $query_cari->where('untuk_pembayaran', $untuk_pembayaran);
        }


        // Sorting berdasarkan tanggal pembuatan
        $sort = $request->get('sort', 'asc');
        $query->orderBy('created_at', $sort);

        // Ambil data perbaikan yang statusnya Proses
        $perbaikan = $query->where('status', 'Proses')->get();

        //  $pemasangan = RekapPemasanganModel::all();

        $pemasangan = RekapPemasanganModel::whereMonth('tgl_aktivasi', Carbon::now()->month)
            ->whereYear('tgl_aktivasi', Carbon::now()->year)
            ->orderBy('tgl_aktivasi', 'desc')
            ->get();
        $total_pemasangan = $pemasangan->count();
        $rekap_pemasangan_limited = $pemasangan->take(5);


        // Data untuk chart mingguan
        $weeklyData = Perbaikan::selectRaw('WEEK(created_at) as week, COUNT(*) as total')
            ->groupBy('week')
            ->pluck('total', 'week');

        // Data untuk chart bulanan
        $monthlyData = Perbaikan::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Data untuk chart tahunan
        $yearlyData = Perbaikan::selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        return view('teknisi.index', compact(
            'rekap_pemasangan_limited',
            'total_pemasangan',
            'pemasangan',
            'perbaikan',
            'pemasangan',
            'sort',
            'weeklyData',
            'monthlyData',
            'yearlyData',
            'pelanggan',
            'query_cari', // Kirimkan query_cari sebagai nilai pencarian
            'jumlah_pembayaran',
            'paket_plg',
            'tanggal',
            'status_pembayaran_display',
            'tanggal_pembayaran',
            'bulan',
            'date_start',
            'date_end',
            'search',
            'created_at',
        ));
    }
    function coba()
    {
        return view('coba');
    }


    public function rekapTeknisi(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $rekap = Perbaikan::selectRaw('teknisi, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        return view('teknisi.rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate'));
    }
}

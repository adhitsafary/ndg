<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\BranchCabangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BranchCabangContoller extends Controller
{
    public function index()
    {
        $branch_cabang = BranchCabangModel::all();
        return view('branch_cabang.index', compact('branch_cabang'));
    }

    public function create()
    {
        return view('branch_cabang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cabang' => 'required',
            'nama_cabang' => 'required',
            'nama_pemilik' => 'required',
            'alamat' => 'required',
            'tanggal_bergabung' => 'required|date',
            'Kepemilikan' => 'required',
            'persentase' => 'required|integer',
        ]);

        BranchCabangModel::create($request->all());
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cabang = BranchCabangModel::findOrFail($id);
        return view('branch_cabang.edit', compact('cabang'));
    }

    public function update(Request $request, $id)
    {
        $cabang = BranchCabangModel::findOrFail($id);
        $cabang->update($request->all());
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        BranchCabangModel::destroy($id);
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }
    public function pelangganDetail(Request $request, $kode_cabang)
    {
        $query = \App\Models\Pelanggan::where('kode_cabang', $kode_cabang)
            ->whereNotIn('status_pembayaran', ['PSB', 'Reactivasi']);

        if ($request->filled('nama')) {
            $query->where('nama_plg', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('paket')) {
            $query->where('paket_plg', 'like', '%' . $request->paket . '%');
        }
        $pelanggan = $query->get();



        // Ambil nilai filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $created_at = $request->input('created_at');
        $updated_at = $request->input('updated_at');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $status_pembayaran = $request->input('status_pembayaran');


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
            $query->where(function ($query) use ($search) {
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
            $query->whereIn('tgl_tagih_plg', $tgl_tagih_plg);
        }

        if (!empty($harga_paket)) {
            $query->whereIn('harga_paket', $harga_paket);
        }

        if (!empty($paket_plg)) {
            $query->whereIn('paket_plg', $paket_plg);
        }


        // Jika status_pembayaran difilter, gunakan nilainya, jika tidak pakai default ['isolir', 'paid', 'unpaid']
        if (!empty($status_pembayaran) && is_array($status_pembayaran)) {
            $query->whereIn('status_pembayaran', $status_pembayaran);
        } else {
            $query->whereIn('status_pembayaran', ['isolir', 'paid', 'unpaid']);
        }


        // Filter berdasarkan bulan pembayaran terakhir
        if ($request->filled('bulan_pembayaran')) {
            $bulan = $request->input('bulan_pembayaran');

            $query->whereHas('pembayaranTerakhir', function ($query) use ($bulan) {
                $query->whereMonth('tanggal_pembayaran', $bulan);
            });

            $query->with(['pembayaranTerakhir' => function ($query) use ($bulan) {
                $query->whereMonth('tanggal_pembayaran', $bulan);
            }]);
        } else {
            // Jika tidak ada filter bulan, tetap ambil pembayaran terakhirnya
            $query->with('pembayaranTerakhir');
        }

        // Debug Query
        Log::info("Query SQL:", ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

        // Paginate hasilnya
        $pelanggan = $query->paginate(100);

        $querySudahBayar = clone $query;
        $queryBelumBayar = clone $query;
        $queryIsolir = clone $query;
        $queryBlock = clone $query;
        $queryUnblock = clone $query;
        $queryfilter = clone $query;


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

        return view('branch_cabang.pelanggan_detail', compact(
            'pelanggan',
            'kode_cabang',
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
}

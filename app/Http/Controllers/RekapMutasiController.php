<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapMutasiController extends Controller
{

    public function index()
    {
        // Mengambil data berdasarkan tanggal tagihan dari tabel 'pelanggan', menghitung jumlah total pelanggan dan total pembayaran per tanggal tagihan
        $pelanggan = Pelanggan::select(
            'tgl_tagih_plg',
            DB::raw('harga_paket'),
            DB::raw('COUNT(id_plg) as jumlah_pelanggan'),
            DB::raw('COUNT(id_plg) * harga_paket as total_pembayaran')
        )
            ->whereNotIn('status_pembayaran', ['PSB', 'Reactivasi']) // Mengecualikan status PSB & Reactivasi
            ->groupBy('tgl_tagih_plg', 'harga_paket')
            ->orderBy('tgl_tagih_plg', 'asc')
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
                'selisih_pembayaran' => $selisihPembayaran,
                'keterangan' => $bayarData->keterangan ?? '-', // Gunakan default jika kosong
                'created_at' => $bayarData->created_at ?? '-', // Gunakan default jika kosong
                'id' => $bayarData->id ?? '-' // Gunakan default jika kosong
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

        return view('mutasi.index', compact('mergedResults'));
    }



    public function pelangganPembayaran($tgl_tagih)
    {
        $pelanggan = BayarPelanggan::where('tgl_tagih_plg', $tgl_tagih)
            ->get();

        return view('pelanggan.index', compact('pelanggan', 'tgl_tagih'));
    }

    public function pelangganBelumBayar($tgl_tagih)
    {
        $pelanggan = Pelanggan::where('tgl_tagih_plg', $tgl_tagih)
            ->whereNotIn('id_plg', function ($query) {
                $query->select('id_plg')->from('bayar_pelanggans');
            })
            ->get();

        return view('pelanggan.index', compact('pelanggan', 'tgl_tagih'));
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

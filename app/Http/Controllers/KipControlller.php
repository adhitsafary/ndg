<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use App\Models\User;
use App\Models\X100c;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KipControlller extends Controller
{




    public function index()
    {
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        // Ambil data pembayaran berdasarkan bulan & tahun
        $pembayaran = BayarPelanggan::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->whereNotNull('admin_name') // Pastikan ada admin_name
            ->get()
            ->groupBy('admin_name'); // Dikelompokkan berdasarkan admin_name

        // Data absensi (kehadiran)
        $absensi = X100c::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get()
            ->groupBy('nama');



        // Data pekerjaan dari Perbaikan (Success)
        $perbaikan = Perbaikan::where('status', 'Success')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        // Data pekerjaan dari PSB (Open)
        $psb = RekapPemasanganModel::where('status', 'open')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        // Gunakan array biasa agar tidak terjadi error
        $indexPekerja = [];

        foreach ($absensi as $nama => $items) {
            $firstEntry = $items->first(); // Ambil entri pertama dari X100c

            $indexPekerja[$nama] = [
                'id' => $firstEntry->id, // Pastikan ID dari X100c masuk
                'nama' => $nama,
                'hadir' => $items->count(),
                'pekerjaan' => 0,
                'pembayaran' => 0
            ];
        }




        // Masukkan data dari Perbaikan (Success)
        foreach ($perbaikan as $item) {
            $admin = trim($item->admin);
            $teknisiList = explode(',', $item->teknisi);

            if (!isset($indexPekerja[$admin])) {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => 0
                ];
            }
            $indexPekerja[$admin]['pekerjaan'] += 1;

            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (!isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 0,
                        'pembayaran' => 0
                    ];
                }
                $indexPekerja[$teknisi]['pekerjaan'] += 1;
            }
        }

        // Masukkan data dari PSB (Open)
        foreach ($psb as $item) {
            $admin = trim($item->admin);
            $teknisiList = explode(',', $item->teknisi);

            if (!isset($indexPekerja[$admin])) {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => 0
                ];
            }
            $indexPekerja[$admin]['pekerjaan'] += 1;

            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (!isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 0,
                        'pembayaran' => 0
                    ];
                }
                $indexPekerja[$teknisi]['pekerjaan'] += 1;
            }
        }

        // Masukkan data dari Pembayaran, cocokkan dengan absensi
        foreach ($pembayaran as $adminName => $items) {
            $adminName = trim($adminName); // Pastikan tidak ada spasi

            // Jika ada di daftar pekerja, tambahkan jumlah pembayaran
            if (isset($indexPekerja[$adminName])) {
                $indexPekerja[$adminName]['pembayaran'] += $items->count();
            } else {
                // Jika tidak ada di daftar pekerja, buat entri baru
                $indexPekerja[$adminName] = [
                    'nama' => $adminName,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => $items->count()
                ];
            }
        }

        return view('kip.index', compact('indexPekerja'));
    }


    public function index00()
    {
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        // Ambil data pembayaran berdasarkan bulan & tahun
        $pembayaran = BayarPelanggan::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->whereNotNull('admin_name') // Pastikan ada admin_name
            ->get()
            ->groupBy('admin_name'); // Dikelompokkan berdasarkan admin_name

        // Data absensi (kehadiran)
        $absensi = X100c::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get()
            ->groupBy('nama');



        // Data pekerjaan dari Perbaikan (Success)
        $perbaikan = Perbaikan::where('status', 'Success')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        // Data pekerjaan dari PSB (Open)
        $psb = RekapPemasanganModel::where('status', 'open')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        // Gunakan array biasa agar tidak terjadi error
        $indexPekerja = [];

        // Masukkan data absensi
        foreach ($absensi as $nama => $items) {
            $indexPekerja[$nama] = [
                'nama' => $nama,
                'hadir' => $items->count(),
                'pekerjaan' => 0,
                'pembayaran' => 0
            ];
        }



        // Masukkan data dari Perbaikan (Success)
        foreach ($perbaikan as $item) {
            $admin = trim($item->admin);
            $teknisiList = explode(',', $item->teknisi);

            if (!isset($indexPekerja[$admin])) {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => 0
                ];
            }
            $indexPekerja[$admin]['pekerjaan'] += 1;

            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (!isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 0,
                        'pembayaran' => 0
                    ];
                }
                $indexPekerja[$teknisi]['pekerjaan'] += 1;
            }
        }

        // Masukkan data dari PSB (Open)
        foreach ($psb as $item) {
            $admin = trim($item->admin);
            $teknisiList = explode(',', $item->teknisi);

            if (!isset($indexPekerja[$admin])) {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => 0
                ];
            }
            $indexPekerja[$admin]['pekerjaan'] += 1;

            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (!isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 0,
                        'pembayaran' => 0
                    ];
                }
                $indexPekerja[$teknisi]['pekerjaan'] += 1;
            }
        }

        // Masukkan data dari Pembayaran, cocokkan dengan absensi
        foreach ($pembayaran as $adminName => $items) {
            $adminName = trim($adminName); // Pastikan tidak ada spasi

            // Jika ada di daftar pekerja, tambahkan jumlah pembayaran
            if (isset($indexPekerja[$adminName])) {
                $indexPekerja[$adminName]['pembayaran'] += $items->count();
            } else {
                // Jika tidak ada di daftar pekerja, buat entri baru
                $indexPekerja[$adminName] = [
                    'nama' => $adminName,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => $items->count()
                ];
            }
        }

        return view('kip.index', compact('indexPekerja'));
    }


    public function index0()
    {
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        $pembayaran = BayarPelanggan::all();

        // Data absensi (kehadiran)
        $absensi = X100c::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get()
            ->groupBy('nama');

        // Data pekerjaan dari Perbaikan (Success)
        $perbaikan = Perbaikan::where('status', 'Success')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        // Data pekerjaan dari PSB (Open)
        $psb = RekapPemasanganModel::where('status', 'open')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        // Gunakan array biasa agar tidak terjadi error
        $indexPekerja = [];

        // Masukkan data absensi
        foreach ($absensi as $nama => $items) {
            $indexPekerja[$nama] = [
                'nama' => $nama,
                'hadir' => $items->count(),
                'pekerjaan' => 0
            ];
        }

        // Masukkan data dari Perbaikan (Success)
        foreach ($perbaikan as $item) {
            $admin = trim($item->admin); // Pastikan admin tidak ada spasi ekstra
            $teknisiList = explode(',', $item->teknisi); // Pisahkan teknisi

            // Tambahkan admin ke daftar pekerja
            if (isset($indexPekerja[$admin])) {
                $indexPekerja[$admin]['pekerjaan'] += 1;
            } else {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 1
                ];
            }

            // Tambahkan teknisi ke daftar pekerja
            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi]['pekerjaan'] += 1;
                } else {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 1
                    ];
                }
            }
        }

        // Masukkan data dari PSB (Open)
        foreach ($psb as $item) {
            $admin = trim($item->admin);
            $teknisiList = explode(',', $item->teknisi);

            // Tambahkan admin ke daftar pekerja
            if (isset($indexPekerja[$admin])) {
                $indexPekerja[$admin]['pekerjaan'] += 1;
            } else {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 1
                ];
            }

            // Tambahkan teknisi ke daftar pekerja
            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi]['pekerjaan'] += 1;
                } else {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 1
                    ];
                }
            }
        }

        return view('kip.index', compact('indexPekerja'));
    }


    public function index1()
    {
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        // Data absensi (kehadiran)
        $absensi = X100c::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get()
            ->groupBy('nama');

        // Data pekerjaan dari Perbaikan (Success)
        $perbaikan = Perbaikan::where('status', 'Success')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get()
            ->groupBy('admin');

        // Data pekerjaan dari PSB (Open)
        $psb = RekapPemasanganModel::where('status', 'open')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get()
            ->groupBy('admin');

        // Gunakan array biasa agar tidak terjadi error
        $indexPekerja = [];

        // Masukkan data absensi
        foreach ($absensi as $nama => $items) {
            $indexPekerja[$nama] = [
                'nama' => $nama,
                'hadir' => $items->count(),
                'pekerjaan' => 0
            ];
        }

        // Masukkan data dari Perbaikan (Success)
        foreach ($perbaikan as $admin => $items) {
            if (isset($indexPekerja[$admin])) {
                $indexPekerja[$admin]['pekerjaan'] += $items->count();
            } else {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => $items->count()
                ];
            }
        }

        // Masukkan data dari PSB (Open)
        foreach ($psb as $admin => $items) {
            if (isset($indexPekerja[$admin])) {
                $indexPekerja[$admin]['pekerjaan'] += $items->count();
            } else {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => $items->count()
                ];
            }
        }

        return view('kip.index', compact('indexPekerja'));
    }


    public function index2()
    {
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        // Filter data berdasarkan bulan dan tahun sekarang
        $user = User::all();

        $absensi = X100c::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        $pembayaran = BayarPelanggan::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        $perbaikan = Perbaikan::whereIn('status', ['Success'])
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        $psb = RekapPemasanganModel::whereIn('status', ['open'])
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        $pelanggan = Pelanggan::orderBy('tgl_tagih_plg', 'asc')->get();
        return view('kip.index', compact('user', 'absensi', 'pembayaran', 'perbaikan', 'psb', 'pelanggan'));
    }
    /**
    /**
     * Display a listing of the resource.
     */

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

    public function show($id)
    {
        // Ambil ulang data dari index
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        $absensi = X100c::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get()
            ->groupBy('nama');

        $perbaikan = Perbaikan::where('status', 'Success')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        $psb = RekapPemasanganModel::where('status', 'open')
            ->whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->get();

        $pembayaran = BayarPelanggan::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->whereNotNull('admin_name')
            ->get()
            ->groupBy('admin_name');

        $indexPekerja = [];

        foreach ($absensi as $nama => $items) {
            $firstEntry = $items->first();
            $indexPekerja[$nama] = [
                'id' => $firstEntry->id,
                'nama' => $nama,
                'hadir' => $items->count(),
                'pekerjaan' => 0,
                'pembayaran' => 0
            ];
        }

        foreach ($perbaikan as $item) {
            $admin = trim($item->admin);
            $teknisiList = explode(',', $item->teknisi);

            if (!isset($indexPekerja[$admin])) {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => 0
                ];
            }
            $indexPekerja[$admin]['pekerjaan'] += 1;

            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (!isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 0,
                        'pembayaran' => 0
                    ];
                }
                $indexPekerja[$teknisi]['pekerjaan'] += 1;
            }
        }

        foreach ($psb as $item) {
            $admin = trim($item->admin);
            $teknisiList = explode(',', $item->teknisi);

            if (!isset($indexPekerja[$admin])) {
                $indexPekerja[$admin] = [
                    'nama' => $admin,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => 0
                ];
            }
            $indexPekerja[$admin]['pekerjaan'] += 1;

            foreach ($teknisiList as $teknisi) {
                $teknisi = trim($teknisi);
                if (!isset($indexPekerja[$teknisi])) {
                    $indexPekerja[$teknisi] = [
                        'nama' => $teknisi,
                        'hadir' => 0,
                        'pekerjaan' => 0,
                        'pembayaran' => 0
                    ];
                }
                $indexPekerja[$teknisi]['pekerjaan'] += 1;
            }
        }

        foreach ($pembayaran as $adminName => $items) {
            $adminName = trim($adminName);
            if (isset($indexPekerja[$adminName])) {
                $indexPekerja[$adminName]['pembayaran'] += $items->count();
            } else {
                $indexPekerja[$adminName] = [
                    'nama' => $adminName,
                    'hadir' => 0,
                    'pekerjaan' => 0,
                    'pembayaran' => $items->count()
                ];
            }
        }

        // Cari pekerja berdasarkan ID
        $pekerja = collect($indexPekerja)->firstWhere('id', $id);

        if (!$pekerja) {
            return redirect()->route('kinerja.index')->with('error', 'Data pekerja tidak ditemukan!');
        }

        return view('kip.show', compact('pekerja'));
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

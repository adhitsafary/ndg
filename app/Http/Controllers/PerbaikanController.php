<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryKeluar;
use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\X100c;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;



class PerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function login()
    {
        return view('auth.login');
    }

    public function index(Request $request)
    {
        $query = Perbaikan::orderBy('created_at', 'desc');

        if ($request->has('cari')) {
            $search = $request->input('cari');
            $query->where(function ($q) use ($search) {
                $q->where('nama_plg', 'like', "%{$search}%")
                    ->orWhere('id_plg', 'like', "%{$search}%");
            });
        }

        $perbaikans = $query->paginate(50);

        return view('perbaikan.index', compact('perbaikans'));
    }




    public function getTeknisiAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return array_merge(...array_map(fn($item) => is_array($item) ? $item : [$item], $decoded));
            }
        }

        return is_array($value) ? $value : [];
    }






    public function index2(Request $request)
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

        // Sorting berdasarkan tanggal pembuatan
        $sort = $request->get('sort', 'asc');
        $query->orderBy('created_at', $sort);

        // Ambil data perbaikan yang statusnya Proses
        $perbaikan = $query->where('status', 'Pending')->get();

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

        return view('perbaikan.index', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }



    public function tiket_perbaikan(Request $request)
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

        // Sorting berdasarkan tanggal pembuatan
        $sort = $request->get('sort', 'asc');
        $query->orderBy('created_at', $sort);

        // Ambil data perbaikan yang statusnya Proses
        $perbaikan = $query->where('status', 'Proses')->get();

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

        return view('perbaikan.tiket', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }


    public function exportPdf(Request $request)
    {
        $query = Perbaikan::query();

        // Tambahkan filter jika perlu

        $perbaikan = $query->get();

        $pdf = Pdf::loadView('perbaikan.pdf', compact('perbaikan'));

        return $pdf->download('perbaikan.pdf');
    }

    /* public function exportExcel(Request $request)
    {
        return Excel::download(new PerbaikanExport($request), 'perbaikan.xlsx');
    } */



    public function store22(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Optional field for user to select a teknisi
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Jika user memilih teknisi
        if ($request->has('teknisi') && !empty($request->teknisi)) {
            $teknisiDipilih = $request->teknisi;

            // Cek apakah teknisi yang dipilih masih dalam status "Proses"
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisiDipilih)
                ->where('status', 'Proses')
                ->exists();

            if ($teknisiStatusProses) {
                return redirect()->back()->with('error', 'Teknisi yang dipilih sedang dalam status Proses. Silakan pilih teknisi lain.');
            }
        } else {
            // Jika user tidak memilih teknisi, pilih secara acak dari teknisi yang tidak "Proses"
            $teknisiAvailable = [];
            foreach ($daftarTeknisi as $teknisi) {
                $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                    ->where('status', 'Proses')
                    ->doesntExist(); // Cari teknisi yang tidak punya status Proses
                if ($teknisiStatusProses) {
                    $teknisiAvailable[] = $teknisi;
                }
            }

            // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
            if (empty($teknisiAvailable)) {
                return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
            }

            // Pilih teknisi secara acak dari daftar teknisi yang tersedia
            $teknisiDipilih = $teknisiAvailable[array_rand($teknisiAvailable)];
        }

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    public function store23(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Optional field for user to select a teknisi
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Jika user memilih teknisi
        if ($request->has('teknisi') && !empty($request->teknisi)) {
            $teknisiDipilih = $request->teknisi;

            // Cek apakah teknisi yang dipilih masih dalam status "Proses"
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisiDipilih)
                ->where('status', 'Proses')
                ->exists();

            if ($teknisiStatusProses) {
                return redirect()->back()->with('error', 'Teknisi yang dipilih sedang dalam status Proses. Silakan pilih teknisi lain.');
            }
        } else {
            // Jika user tidak memilih teknisi, pilih secara acak dari teknisi yang tidak "Proses"
            $teknisiAvailable = [];
            foreach ($daftarTeknisi as $teknisi) {
                $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                    ->where('status', 'Proses')
                    ->doesntExist(); // Cari teknisi yang tidak punya status Proses
                if ($teknisiStatusProses) {
                    $teknisiAvailable[] = $teknisi;
                }
            }

            // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
            if (empty($teknisiAvailable)) {
                return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
            }

            // Pilih teknisi secara acak dari daftar teknisi yang tersedia
            $teknisiDipilih = $teknisiAvailable[array_rand($teknisiAvailable)];
        }

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        // Kirim pemberitahuan ke Telegram
        $botToken = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
        $chatId = '5985430823';
        $message = "📣 *Perbaikan Baru Diterima*\n"
            . "🆔 ID Pelanggan: {$perbaikan->id_plg}\n"
            . "👤 Nama: {$perbaikan->nama_plg}\n"
            . "📍 Alamat: {$perbaikan->alamat_plg}\n"
            . "📞 Telepon: {$perbaikan->no_telepon_plg}\n"
            . "📦 Paket: {$perbaikan->paket_plg}\n"
            . "🔧 Teknisi: {$perbaikan->teknisi}\n"
            . "📋 Keterangan: {$perbaikan->keterangan}\n"
            . "🚦 Status: {$perbaikan->status}";

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ];

        $client = new \GuzzleHttp\Client();
        $client->post($url, ['form_params' => $data]);

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'nullable',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Optional field for user to select a teknisi
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Jika user memilih teknisi
        if ($request->has('teknisi') && !empty($request->teknisi)) {
            $teknisiDipilih = $request->teknisi;

            // Cek apakah teknisi yang dipilih masih dalam status "Proses"
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisiDipilih)
                ->where('status', 'Proses')
                ->exists();

            if ($teknisiStatusProses) {
                return redirect()->back()->with('error', 'Teknisi yang dipilih sedang dalam status Proses. Silakan pilih teknisi lain.');
            }
        } else {
            // Jika user tidak memilih teknisi, pilih secara acak dari teknisi yang tidak "Proses"
            $teknisiAvailable = [];
            foreach ($daftarTeknisi as $teknisi) {
                $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                    ->where('status', 'Proses')
                    ->doesntExist(); // Cari teknisi yang tidak punya status Proses
                if ($teknisiStatusProses) {
                    $teknisiAvailable[] = $teknisi;
                }
            }

            // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
            if (empty($teknisiAvailable)) {
                return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
            }

            // Pilih teknisi secara acak dari daftar teknisi yang tersedia
            $teknisiDipilih = $teknisiAvailable[array_rand($teknisiAvailable)];
        }

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        // Kirim notifikasi ke Telegram
        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    private function sendTelegramNotification2($perbaikan)
    {
        $token = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
        $chat_id = '5985430823';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $message =
            "📣 *Notifikasi Perbaikan Baru*\n" .
            "========================\n" .
            "🆔 *ID Pelanggan :* {$perbaikan->id_plg}\n" .
            "👤 *Nama :* {$perbaikan->nama_plg}\n" .
            "📍 *Alamat :* {$perbaikan->alamat_plg}\n" .
            "📞 *Telepon :* {$perbaikan->no_telepon_plg}\n" .
            "📦 *Paket :* {$perbaikan->paket_plg}\n" .
            "🔧 *Teknisi :* {$perbaikan->teknisi}\n" .
            "📋 *Keterangan :* {$perbaikan->keterangan}\n" .
            "🚦 *Status :* {$perbaikan->status}\n";

        $client = new \GuzzleHttp\Client();

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





    public function store1(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            1 => 'Tim 1 Deden - Agis',
            2 => 'Tim 2 Mursidi - Dindin',
            3 => 'Tim 3 Isep - Indra'
        ];

        // Cek teknisi yang saat ini tidak memiliki status "Proses"
        $teknisiAvailable = [];
        foreach ($daftarTeknisi as $key => $teknisi) {
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                ->where('status', 'Proses')
                ->doesntExist(); // Cari teknisi yang tidak punya status Proses
            if ($teknisiStatusProses) {
                $teknisiAvailable[$key] = $teknisi;
            }
        }

        // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
        if (empty($teknisiAvailable)) {
            return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
        }

        // Pilih teknisi secara acak dari daftar teknisi yang tersedia
        $teknisiAcak = array_rand($teknisiAvailable);
        $teknisiDipilih = $teknisiAvailable[$teknisiAcak];

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih setelah pengecekan
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }




    //PSB

    public function create_wo()
    {
        $teknisi = X100c::whereDate('created_at', Carbon::today())
            ->orderBy('nama')
            ->get();

        $pelanggan = Pelanggan::select('id_plg', 'nama_plg', 'alamat_plg', 'no_telepon_plg', 'paket_plg', 'odp', 'maps')
            ->get();

        $inventory = Inventory::select('nm_brg', 'jml_brg', 'satuan', 'harga_satuan', 'kategori')
            ->where('jml_brg', '>', 0) // Hanya ambil barang yang jumlahnya lebih dari 0
            ->get();

        return view('perbaikan.create_wo', compact('teknisi', 'pelanggan', 'inventory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_awal(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;


        $perbaikan->save();

        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data PSB berhasil ditambahkan');
    }

    public function store_baru(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable',
            'maps' => 'nullable',
            'odp' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra',
            'Tim 4 Adit'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;
        $perbaikan->info = $request->info;


        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;


        // Simpan data terlebih dahulu agar created_at terisi
        $perbaikan->save();

        // Cari nomor urut terakhir
        $lastTiket = Perbaikan::max('nomor_tiket');
        $nomorTiket = $lastTiket ? $lastTiket + 1 : 1; // Jika belum ada, mulai dari 1

        // Format nomor tiket dengan leading zero (4 digit)
        $perbaikan->nomor_tiket = str_pad($nomorTiket, 4, '0', STR_PAD_LEFT);

        // Generate kode tiket berdasarkan nomor_tiket dan id_plg
        $perbaikan->kd_tiket = $perbaikan->nomor_tiket; //. '-' . $perbaikan->id_plg;

        // Simpan kode tiket dan nomor tiket
        $perbaikan->save();

        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.tiket')->with('success', 'Data PSB berhasil ditambahkan');
    }

    public function store_terakhir(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable',
            'maps' => 'nullable',
            'odp' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra',
            'Tim 4 Adit'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;
        $perbaikan->info = $request->info;
        $perbaikan->teknisi = $teknisiDipilih;

        // Simpan data terlebih dahulu agar created_at terisi
        $perbaikan->save();

        // Cari nomor urut terakhir
        $lastTiket = Perbaikan::max('nomor_tiket');
        $nomorTiket = $lastTiket ? $lastTiket + 1 : 1; // Jika belum ada, mulai dari 1

        // Format nomor tiket dengan leading zero (4 digit)
        $perbaikan->nomor_tiket = str_pad($nomorTiket, 4, '0', STR_PAD_LEFT);

        // Generate kode tiket berdasarkan nomor_tiket dan id_plg
        $perbaikan->kd_tiket = $perbaikan->nomor_tiket;

        // Simpan kode tiket dan nomor tiket
        $perbaikan->save();

        // Kirim pesan ke nomor pelanggan
        $this->sendMessageToCustomer($perbaikan);

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.tiket')->with('success', 'Data PSB berhasil ditambahkan');
    }



    public function create()
    {
        $teknisi = X100c::whereDate('created_at', Carbon::today())
            ->orderBy('nama')
            ->get();

        $pelanggan = Pelanggan::select('id_plg', 'nama_plg', 'alamat_plg', 'no_telepon_plg', 'paket_plg', 'odp', 'maps')
            ->get();

        $inventory = Inventory::select('nm_brg', 'jml_brg', 'satuan', 'harga_satuan', 'kategori')
            ->where('jml_brg', '>', 0) // Hanya ambil barang yang jumlahnya lebih dari 0
            ->get();

        return view('perbaikan.create', compact('teknisi', 'pelanggan', 'inventory'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'nullable',
            'alamat_plg' => 'nullable',
            'no_telepon_plg' => 'nullable',
            'paket_plg' => 'nullable',
            'keterangan' => 'nullable',
            'teknisi' => 'nullable|array',
            'maps' => 'nullable',
            'odp' => 'nullable',
            'kategori' => 'nullable',
        ]);

        // Daftar teknisi
        $daftarTeknisi = [
            'deden, Agisdut',
            'Mursidi, Didin',
            'Isep, Indra',
            'Johan, Gilang',
            'Adit'
        ];

        $admin = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        // Pilih teknisi (jika tidak ada, pilih acak)
        $teknisiDipilih = $request->has('teknisi') && is_array($request->teknisi)
            ? implode(', ', $request->teknisi)
            : $daftarTeknisi[array_rand($daftarTeknisi)];

        $input = $request->all();
        $input['teknisi'] = json_encode($request->teknisi ?? []);
        $input['inventory_keluar'] = json_encode($request->inventory ?? []);
        $input['total_biaya'] = 0;

        if (!empty($request->inventory) && is_array($request->inventory)) {
            foreach ($request->inventory as $inv) {
                $input['total_biaya'] += ($inv['jml_brg'] ?? 0) * ($inv['harga_satuan'] ?? 0);
            }
        }

        // Simpan data perbaikan
        $perbaikan = new Perbaikan();

        $perbaikan->fill($input);
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->save();

        // Buat nomor tiket
        $lastTiket = (int) Perbaikan::max('nomor_tiket');
        $nomorTiket = $lastTiket + 1;
        $perbaikan->nomor_tiket = str_pad($nomorTiket, 4, '0', STR_PAD_LEFT);
        $perbaikan->kd_tiket = $perbaikan->nomor_tiket;
        $perbaikan->admin = $admin;
        $perbaikan->kategori = "ndg";
        $perbaikan->save();

        if (!empty($request->inventory) && is_array($request->inventory)) {
            foreach ($request->inventory as $nm_brg => $inv) {
                // Pastikan semua key yang dibutuhkan ada
                if (!isset($inv['jml_brg'], $inv['harga_satuan'])) {
                    dd("Data tidak lengkap:", $inv);
                }

                // Jika jumlah barang = 0, lewati
                if ((int) $inv['jml_brg'] <= 0) {
                    continue;
                }

                // Simpan ke tabel inventory_keluar
                InventoryKeluar::create([
                    'nm_brg' => $nm_brg,  // Gunakan $nm_brg dari key array
                    'jml_brg' => $inv['jml_brg'],
                    'harga_satuan' => $inv['harga_satuan'],
                    'perbaikan_id' => $perbaikan->id,
                ]);

                // Cek apakah barang ada di tabel inventory
                $inventory = Inventory::where('nm_brg', $nm_brg)->first();
                if (!$inventory) {
                    dd("Barang tidak ditemukan di inventory:", $nm_brg);
                }

                // Kurangi stok barang di inventory
                $inventory->jml_brg = max(0, $inventory->jml_brg - (int) $inv['jml_brg']);
                $inventory->save();
            }
        }

        // Kirim notifikasi
        $this->sendMessageToCustomer($perbaikan);
        $this->sendTelegramNotification($perbaikan);

        if ($perbaikan) {
            return redirect()->route('perbaikan.index')
                ->with('success', 'Data Perbaikan Berhsil di Tambahkan', $perbaikan->nama_plg);
        } else {
            return redirect()->route('perbaikan.index')
                ->white('error', 'Data Perbaikan Gagal di Tambahkan', $perbaikan->nama_plg . '. Silahkan Coba lagi');
        }
    }



    public function store_wo(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'nullable',
            'alamat_plg' => 'nullable',
            'no_telepon_plg' => 'nullable',
            'paket_plg' => 'nullable',
            'keterangan' => 'nullable',
            'teknisi' => 'nullable|array',
            'maps' => 'nullable',
            'odp' => 'nullable',
            'kategori' => 'nullable',
        ]);

        // Daftar teknisi
        $daftarTeknisi = [
            'deden, Agisdut',
            'Mursidi, Didin',
            'Isep, Indra',
            'Johan, Gilang',
            'Adit'
        ];

        $admin = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        // Pilih teknisi (jika tidak ada, pilih acak)
        $teknisiDipilih = $request->has('teknisi') && is_array($request->teknisi)
            ? implode(', ', $request->teknisi)
            : $daftarTeknisi[array_rand($daftarTeknisi)];

        $input = $request->all();
        $input['teknisi'] = json_encode($request->teknisi ?? []);
        $input['inventory_keluar'] = json_encode($request->inventory ?? []);
        $input['total_biaya'] = 0;

        if (!empty($request->inventory) && is_array($request->inventory)) {
            foreach ($request->inventory as $inv) {
                $input['total_biaya'] += ($inv['jml_brg'] ?? 0) * ($inv['harga_satuan'] ?? 0);
            }
        }

        // Simpan data perbaikan
        $perbaikan = new Perbaikan();

        $perbaikan->fill($input);
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->save();

        // Buat nomor tiket
        $lastTiket = (int) Perbaikan::max('nomor_tiket');
        $nomorTiket = $lastTiket + 1;
        $perbaikan->nomor_tiket = str_pad($nomorTiket, 4, '0', STR_PAD_LEFT);
        $perbaikan->kd_tiket = $perbaikan->nomor_tiket;
        $perbaikan->admin = $admin;
        $perbaikan->kategori = "wo";
        $perbaikan->save();

        if (!empty($request->inventory) && is_array($request->inventory)) {
            foreach ($request->inventory as $nm_brg => $inv) {
                // Pastikan semua key yang dibutuhkan ada
                if (!isset($inv['jml_brg'], $inv['harga_satuan'])) {
                    dd("Data tidak lengkap:", $inv);
                }

                // Jika jumlah barang = 0, lewati
                if ((int) $inv['jml_brg'] <= 0) {
                    continue;
                }

                // Simpan ke tabel inventory_keluar
                InventoryKeluar::create([
                    'nm_brg' => $nm_brg,  // Gunakan $nm_brg dari key array
                    'jml_brg' => $inv['jml_brg'],
                    'harga_satuan' => $inv['harga_satuan'],
                    'perbaikan_id' => $perbaikan->id,
                ]);

                // Cek apakah barang ada di tabel inventory
                $inventory = Inventory::where('nm_brg', $nm_brg)->first();
                if (!$inventory) {
                    dd("Barang tidak ditemukan di inventory:", $nm_brg);
                }

                // Kurangi stok barang di inventory
                $inventory->jml_brg = max(0, $inventory->jml_brg - (int) $inv['jml_brg']);
                $inventory->save();
            }
        }

        // Kirim notifikasi
        $this->sendMessageToCustomer($perbaikan);
        $this->sendTelegramNotification($perbaikan);

        if ($perbaikan) {
            return redirect()->route('perbaikan.index')
                ->with('success', 'Data Perbaikan Berhsil di Tambahkan', $perbaikan->nama_plg);
        } else {
            return redirect()->route('perbaikan.index')
                ->white('error', 'Data Perbaikan Gagal di Tambahkan', $perbaikan->nama_plg . '. Silahkan Coba lagi');
        }
    }









    public function create222()
    {
        $today = now()->toDateString();
        $user_x100c = X100c::whereDate('waktu', $today)->pluck('nama')->toArray();


        $inventories = Inventory::where('jml_brg', '>', 0)->get(); // Hanya tampilkan yang stok tersedia
        return view('perbaikan.create', compact('inventories', 'user_x100c'));
    }



    public function store222(Request $request)
    {
        try {
            // Debugging: Lihat data request sebelum validasi
            Log::info('Data request sebelum validasi:', $request->all());

            $validatedData = $request->validate([
                'id_plg' => 'required|string|max:255',
                'nama_plg' => 'required|string|max:255',
                'alamat_plg' => 'required|string|max:255',
                'no_telepon_plg' => 'required|string|max:20',
                'paket_plg' => 'required|string|max:255',
                'keterangan' => 'required|string',
                'teknisi' => 'required|string|max:255',
                'inventory_id' => 'required|array',
                'inventory_id.*' => 'exists:inventory,id', // Pastikan inventory ada
                'jumlah_digunakan' => 'required|array',
                'jumlah_digunakan.*' => 'integer|min:1',
            ]);

            // Debugging: Lihat data setelah validasi berhasil
            Log::info('Data setelah validasi:', $validatedData);

            DB::beginTransaction();
            $totalHargaSemua = 0;

            // Simpan ke tabel Perbaikan
            $perbaikan = Perbaikan::create([
                'id_plg' => $request->id_plg,
                'nama_plg' => $request->nama_plg,
                'alamat_plg' => $request->alamat_plg,
                'no_telepon_plg' => $request->no_telepon_plg,
                'paket_plg' => $request->paket_plg,
                'odp' => $request->odp ?? null,
                'maps' => $request->maps ?? null,
                'keterangan' => $request->keterangan,
                'teknisi' => $request->teknisi,
                'status' => 'Pending',
                'kd_tiket' => Str::upper(uniqid('TIKET-')),
                'nomor_tiket' => now()->format('YmdHis') . rand(100, 999),
                'info' => $request->info ?? null,
            ]);

            // Simpan data inventory ke InventoryKeluar
            foreach ($request->inventory_id as $index => $inventoryId) {
                $inventory = Inventory::find($inventoryId);

                // Debugging: Pastikan inventory ditemukan
                if (!$inventory) {
                    throw new \Exception('Barang dengan ID ' . $inventoryId . ' tidak ditemukan.');
                }

                $jumlahDigunakan = $request->jumlah_digunakan[$index];

                // Debugging: Cek nilai inventory ID dan jumlah yang digunakan
                Log::info('Processing Inventory:', [
                    'inventory_id' => $inventoryId,
                    'jumlah_digunakan' => $jumlahDigunakan,
                    'stok_tersedia' => $inventory->jml_brg,
                ]);

                if ($inventory->jml_brg < $jumlahDigunakan) {
                    throw new \Exception('Stok tidak mencukupi untuk ' . $inventory->nm_brg);
                }

                $harga_total = $jumlahDigunakan * $inventory->harga_satuan;
                $totalHargaSemua += $harga_total;

                // Kurangi stok di inventory
                $inventory->decrement('jml_brg', $jumlahDigunakan);

                // Simpan ke inventory_keluar
                InventoryKeluar::create([
                    'inventory_id' => $inventory->id,
                    'id_plg' => $request->id_plg,
                    'nm_brg' => $inventory->nm_brg,
                    'jumlah_keluar' => $jumlahDigunakan,
                    'harga_satuan' => $inventory->harga_satuan,
                    'total_harga' => $harga_total,
                    'admin' => auth()->user()->name ?? 'Admin',
                    'tanggal_keluar' => now(),
                    'kd_tiket' => $perbaikan->kd_tiket,
                ]);
            }

            DB::commit();
            return redirect()->route('perbaikan.tiket')->with('success', 'Data perbaikan berhasil ditambahkan! Total harga barang: Rp' . number_format($totalHargaSemua));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error di store(): ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }




    private function sendMessageToCustomer1($perbaikan)
    {
        $token = "uPQuNAPZ2docn9iMxz9Y"; // Ganti dengan token yang sesuai
        $nama_plg = $perbaikan->nama_plg;
        $kd_tiket = $perbaikan->kd_tiket;

        // Ambil informasi pelanggan
        $pelanggan = Pelanggan::where('id_plg', $perbaikan->id_plg)->first();
        if (!$pelanggan) {
            return back()->withErrors('Pelanggan tidak ditemukan.');
        }

        // Hitung tanggal jatuh tempo
        $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
        $formattedDate = $tglTagihPlg->format('d F Y');

        // Tentukan jenis paket berdasarkan nilai paket_plg
        $paket = match ($pelanggan->paket_plg) {
            1 => '5 Mbps',
            2 => '10 Mbps',
            3 => '15 Mbps',
            4 => '25 Mbps',
            default => 'Default',
        };


        // Format pesan yang akan dikirim
        $message = "\n";
        $message = "*Assalamualaikum selamat siang Bapak/Ibu $nama_plg,*\n\n";
        $message .= "Tiket perbaikan dengan kode: *{$kd_tiket}* telah berhasil diproses.\n";
        $message .= "*Pelanggan YTH:*\n";
        $message .= "*{$pelanggan->nama_plg} - {$pelanggan->alamat_plg}*\n";
        $message .= "Masa aktif s/d {$formattedDate}\n\n";
        $message .= "Mohon tunggu kedatangan Teknisi Net Digital Group.\n";
        $message .= "Terimakasih🙏.\n\n";

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $perbaikan->no_telepon_plg,
                'message' => $message,
                'delay' => '5',
            ]);

            if (!$response->successful()) {
                return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
            }

            return true;
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function sendMessageToCustomer($perbaikan)
    {
        $token = "uPQuNAPZ2docn9iMxz9Y"; // Ganti dengan token yang sesuai
        $nama_plg = $perbaikan->nama_plg;
        $kd_tiket = $perbaikan->kd_tiket;

        // Ambil informasi pelanggan
        $pelanggan = Pelanggan::where('id_plg', $perbaikan->id_plg)->first();
        if (!$pelanggan) {
            return back()->withErrors('Pelanggan tidak ditemukan.');
        }

        // Hitung tanggal jatuh tempo
        $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
        $formattedDate = $tglTagihPlg->format('d F Y');

        // Tentukan jenis paket berdasarkan nilai paket_plg
        $paket = match ($pelanggan->paket_plg) {
            1 => '5 Mbps',
            2 => '10 Mbps',
            3 => '15 Mbps',
            4 => '25 Mbps',
            default => "default",
        };


        // Format pesan yang akan dikirim
        $message = "*📢 PEMBERITAHUAN PERBAIKAN 📢*\n\n";
        $message .= "*Assalamualaikum, Bapak/Ibu $nama_plg,*\n\n";
        $message .= "Tiket perbaikan dengan kode: *{$kd_tiket}* telah berhasil dibuat dan sedang diproses.\n";
        $message .= "Mohon bersabar, teknisi kami akan segera datang untuk menangani permasalahan Anda.\n\n";
        $message .= "*🔹 Detail Pelanggan 🔹*\n";
        $message .= "👤 *Nama:* {$pelanggan->nama_plg}\n";
        $message .= "🏠 *Alamat:* {$pelanggan->alamat_plg}\n";
        $message .= "🌐 *Jenis Paket:* {$paket}\n";
        $message .= "📅 *Masa Aktif:* s/d {$formattedDate}\n\n";
        $message .= "Terima kasih atas kepercayaan Anda menggunakan layanan *Net Digital Group*.\n\n";
        $message .= "🙏 Kami siap membantu Anda kapan saja! 🙌\n";

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $perbaikan->no_telepon_plg,
                'message' => $message,
                'delay' => '5',
            ]);

            if (!$response->successful()) {
                return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
            }

            return true;
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function store_psb(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;


        $perbaikan->save();

        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data PSB berhasil ditambahkan');
    }

    public function store_psb2(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        // Buat entri baru untuk PSB
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'PSB'; // Status PSB
        $perbaikan->save();

        // Kirim notifikasi ke Telegram
        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data PSB berhasil ditambahkan');
    }

    private function sendTelegramNotification($perbaikan)
    {
        $adminName = auth()->user()->name;
        $token = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';

        $token = '';
        $chat_id = '-4743236105';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $message =
            "========================\n" .
            "📣 *Notifikasi Perbaikan Baru*\n" .
            "========================\n" .
            "🆔 *ID Pelanggan :* {$perbaikan->id_plg}\n" .
            "👤 *Nama :* {$perbaikan->nama_plg}\n" .
            "📍 *Alamat :* {$perbaikan->alamat_plg}\n" .
            "🗺️ *Maps :* \n" .
            "📞 *Telepon :* {$perbaikan->no_telepon_plg}\n" .
            "📦 *Paket :* {$perbaikan->paket_plg}\n" .
            "🔧 *Teknisi :* {$perbaikan->teknisi}\n" .
            "🚦 *Keterangan :* {$perbaikan->keterangan}\n\n" .
            "🙎🏻‍♂️ *Admin :* {$adminName}\n";


        $client = new \GuzzleHttp\Client();

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





    public function getPelanggan($input)
    {
        // Coba cari pelanggan berdasarkan ID atau Nama Pelanggan
        $pelanggan = Pelanggan::where('id_plg', $input)
            ->orWhere('nama_plg', 'LIKE', '%' . $input . '%')
            ->first();

        if ($pelanggan) {
            return response()->json([
                'id_plg' => $pelanggan->id_plg,
                'nama_plg' => $pelanggan->nama_plg,
                'alamat_plg' => $pelanggan->alamat_plg,
                'no_telepon_plg' => $pelanggan->no_telepon_plg,
                'paket_plg' => $pelanggan->paket_plg,
                'odp' => $pelanggan->odp,
                'maps' => $pelanggan->maps
            ]);
        } else {
            return response()->json(null);
        }
    }

    public function searchPelanggan(Request $request)
    {
        $search = $request->input('search');

        $pelanggan = Pelanggan::where('nama_plg', 'like', '%' . $search . '%')
            ->get(['id_plg', 'nama_plg']); // Pilih kolom yang diperlukan untuk select2

        $results = [];

        foreach ($pelanggan as $p) {
            $results[] = [
                'id' => $p->id_plg,  // Ini yang akan menjadi value dari option
                'text' => $p->nama_plg  // Ini yang akan tampil di dropdown
            ];
        }

        return response()->json($results);
    }


    public function print($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        $inventory_keluar = json_decode($perbaikan->inventory_keluar, true);
        return view('perbaikan.print', compact('perbaikan', 'inventory_keluar'));
    }


    public function show2($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Pastikan hanya decode jika masih dalam format string JSON
        $inventory_keluar = is_string($perbaikan->inventory_keluar)
            ? json_decode($perbaikan->inventory_keluar, true)
            : $perbaikan->inventory_keluar;

        return view('perbaikan.show', compact('perbaikan', 'inventory_keluar'));
    }



    public function show($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Decode inventory_keluar jika masih dalam format JSON
        $inventory_keluar = json_decode($perbaikan->inventory_keluar, true);

        return view('perbaikan.show', compact('perbaikan', 'inventory_keluar'));
    }

    public function edit($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Ambil teknisi yang dibuat hari ini
        $teknisi = X100c::whereDate('created_at', Carbon::today())
            ->orderBy('nama')
            ->get();

        $pelanggan = Pelanggan::select('id_plg', 'nama_plg', 'alamat_plg', 'no_telepon_plg', 'paket_plg', 'odp', 'maps')
            ->get();

        $inventory = Inventory::select('nm_brg', 'jml_brg', 'satuan', 'harga_satuan', 'kategori')
            ->where('jml_brg', '>', 0)
            ->get();

        // Decode teknisi yang sebelumnya dipilih
        $selectedTeknisi = json_decode($perbaikan->teknisi, true) ?? [];

        return view('perbaikan.edit', compact('perbaikan', 'teknisi', 'pelanggan', 'inventory', 'selectedTeknisi'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'nullable',
            'alamat_plg' => 'nullable',
            'no_telepon_plg' => 'nullable',
            'paket_plg' => 'nullable',
            'keterangan' => 'nullable',
            'teknisi' => 'nullable|array',
            'maps' => 'nullable',
            'odp' => 'nullable',
            'kategori' => 'nullable',
        ]);

        $perbaikan = Perbaikan::findOrFail($id);
        $input = $request->all();
        $input['teknisi'] = json_encode($request->teknisi ?? []);
        $input['inventory_keluar'] = json_encode($request->inventory ?? []);
        $input['total_biaya'] = 0;

        if (!empty($request->inventory) && is_array($request->inventory)) {
            foreach ($request->inventory as $inv) {
                $input['total_biaya'] += ($inv['jml_brg'] ?? 0) * ($inv['harga_satuan'] ?? 0);
            }
        }

        $perbaikan->update($input);

        InventoryKeluar::where('perbaikan_id', $id)->delete();

        if (!empty($request->inventory) && is_array($request->inventory)) {
            foreach ($request->inventory as $nm_brg => $inv) {
                if (!isset($inv['jml_brg'], $inv['harga_satuan']) || (int) $inv['jml_brg'] <= 0) {
                    continue;
                }

                InventoryKeluar::create([
                    'nm_brg' => $nm_brg,
                    'jml_brg' => $inv['jml_brg'],
                    'harga_satuan' => $inv['harga_satuan'],
                    'perbaikan_id' => $perbaikan->id,
                ]);

                $inventory = Inventory::where('nm_brg', $nm_brg)->first();
                if ($inventory) {
                    $inventory->jml_brg = max(0, $inventory->jml_brg - (int) $inv['jml_brg']);
                    $inventory->save();
                }
            }
        }

        return redirect()->route('perbaikan.index')->with('success', 'Data Perbaikan Berhasil Diperbarui');
    }

    public function destroy(string $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikan->delete();

        if ($perbaikan) {
            return redirect()->route('perbaikan.index')
                ->with('success', 'Data Perbaikan Berhsil di hapus', $perbaikan->nama_plg);
        } else {
            return redirect()->route('perbaikan.index')
                ->white('error', 'Data Perbaikan Gagal di hapus', $perbaikan->nama_plg . '. Silahkan Coba lagi');
        }
    }



    public function teknisi(Request $request)
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

        return view('perbaikan.teknisi', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }


    function home2()
    {
        return view('home2');
    }

    public function rekapTeknisi_asli()
    {
        $today = Carbon::now();
        $startDate = $today->copy()->startOfMonth();
        $endDate = $today->copy()->endOfMonth();
        $perbaikan = Perbaikan::findOrFail();

        $rekap = Perbaikan::selectRaw('teknisi, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('teknisi')
            ->get();

        // Reset rekap pada tanggal 25
        if ($today->day == 25) {
            // Hapus atau reset data rekap jika perlu
            // Misalnya dengan menambahkan kode reset di sini
        }

        return view('perbaikan.rekap_teknisi', compact('rekap'));
    }



    // Menambahkan schedule untuk reset data setiap tanggal 25
    public function resetTeknisiData()
    {
        $today = Carbon::now();

        if ($today->day == 25) {
            // Reset data teknisi
            // Misalnya menghapus atau mereset data tertentu jika diperlukan
            // Bisa menggunakan query builder atau model untuk mereset data
        }
    }

    public function rekapTeknisi1(Request $request)
    {
        $query = Perbaikan::query();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        // Filter tanggal
        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('teknisi', 'like', "%{$search}%");
            });
        }

        // Ambil data rekap teknisi dan total perbaikan
        $rekap = $query->selectRaw('teknisi, COUNT(*) as total')
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        // Ambil semua data hasil filter untuk ditampilkan di tabel detail
        $perbaikan = $query->get();

        return view('perbaikan.rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate', 'perbaikan'));
    }

    public function rekapTeknisi(Request $request)
    {
        $perbaikan = Perbaikan::all();
        $query = Perbaikan::query();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        // Filter tanggal
        $perbaikan->whereBetween('created_at', [$startDate, $endDate]);

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $perbaikan->where(function ($perbaikan) use ($search) {
                $perbaikan->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('teknisi', 'like', "%{$search}%");
            });
        }

        // Ambil data rekap teknisi dan total perbaikan
        $rekap = $query->selectRaw('teknisi, COUNT(*) as total')
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        // Ambil semua data hasil filter untuk ditampilkan di tabel detail

        return view('perbaikan.rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate', 'perbaikan'));
    }



    public function printRekapTeknisi(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $rekap = Perbaikan::selectRaw('teknisi, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        $pdf = Pdf::loadView('perbaikan.print_rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate'));

        return $pdf->download('rekap_teknisi_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.pdf');
    }


    public function resetData()
    {
        $today = Carbon::now();
        $startDate = $today->copy()->startOfMonth();
        $endDate = $today->copy()->endOfMonth();

        // Hapus atau reset data perbaikan dari bulan ini
        DB::table('perbaikan')->whereBetween('created_at', [$startDate, $endDate])->delete();

        return redirect()->route('perbaikan.rekapTeknisi')->with('status', 'Data perbaikan bulanan telah direset.');
    }

    public function selesai($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikan->status = 'selesai'; // Ubah status menjadi 'selesai'
        $perbaikan->save();
        // Kirim pesan ke nomor pelanggan
        $this->sendMessageToCustomerSelesai($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Perbaikan telah ditandai selesai');
    }

    private function sendMessageToCustomerSelesai($perbaikan)
    {
        $token = "uPQuNAPZ2docn9iMxz9Y"; // Ganti dengan token yang sesuai
        $nama_plg = $perbaikan->nama_plg;
        $kd_tiket = $perbaikan->kd_tiket;

        // Pesan pemberitahuan setelah perbaikan selesai
        $message = "*Assalamualaikum, Bapak/Ibu $nama_plg,*\n\n";
        $message .= "Perbaikan jaringan internet Anda dengan kode tiket *{$kd_tiket}* telah *selesai* dan berjalan dengan normal kembali. \n\n";
        $message .= "Terima kasih telah mempercayakan layanan kami. Jika ada kendala lebih lanjut, jangan ragu untuk menghubungi kami. 🙏😊\n\n";
        $message .= "*Net Digital Group*";

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $perbaikan->no_telepon_plg,
                'message' => $message,
                'delay' => '5',
            ]);

            if (!$response->successful()) {
                return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
            }

            return true;
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

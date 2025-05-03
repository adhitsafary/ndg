<?php

namespace App\Http\Controllers;

use App\Models\BotToken;
use App\Models\BranchCabangModel;
use App\Models\GeneratorId;
use App\Models\Inventory;
use App\Models\InventoryKeluar;
use App\Models\KaryawanModel;
use App\Models\KasbonModel;
use App\Models\Modem;
use App\Models\NetDigitalGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;
use App\Models\RekapPemasanganModel;
use App\Models\X100c;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RekapPemasanganController extends Controller
{

    public function home()
    {

        $rekap_pemasangan = RekapPemasanganModel::all();

        // Kirim data ke view
        return view('rekap_pemasangan.index', compact('rekap_pemasangan'));
    }


    public function detail($id)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id);
        return view('rekap_pemasangan.detail', compact('rekap_pemasangan'));
    }


    public function index(Request $request)
    {
        $query = RekapPemasanganModel::query()->orderBy('created_at', 'desc');

        // Ambil input
        $search = $request->input('search');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $nama = $request->input('nama');
        $marketing = $request->input('marketing');
        $nominal = $request->input('nominal');
        $tgl_pengajuan = $request->input('tgl_pengajuan');
        $tgl_aktivasi = $request->input('tgl_aktivasi');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $registrasi = $request->input('registrasi');

        // Filter search
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('no_telpon', 'like', "%{$search}%")
                    ->orWhere('paket_plg', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('tgl_aktivasi', 'like', "%{$search}%")
                    ->orWhere('teknisi', 'like', "%{$search}%")
                    ->orWhere('admin', 'like', "%{$search}%")
                    ->orWhere('marketing', 'like', "%{$search}%")
                    ->orWhere('registrasi', 'like', "%{$search}%");
            });
        }


        if ($request->filled('created_at_dari')) {
            $query->whereDate('created_at', '>=', $request->created_at_dari);
        }

        if ($request->filled('created_at_sampai')) {
            $query->whereDate('created_at', '<=', $request->created_at_sampai);
        }

        if ($tgl_pengajuan) {
            $query->where('tgl_pengajuan', $tgl_pengajuan);
        }

        if ($registrasi) {
            $query->where('registrasi', $registrasi);
        }

        if ($tgl_aktivasi) {
            $query->whereDate('tgl_aktivasi', $tgl_aktivasi);
        }

        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        if ($nama) {
            $query->where('nama', 'like', "%{$nama}%");
        }

        if ($marketing) {
            $query->where('marketing', 'like', "%{$marketing}%");
        }

        if ($tgl_tagih_plg) {
            $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        }

        // Data bulanan dan rekap
        $query_bulanan = RekapPemasanganModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('created_at', 'desc')
            ->get();


        $query_semua = RekapPemasanganModel::orderBy('created_at', 'desc')->get();
        $totalBiaya = $query_bulanan->sum('biaya');
        $totalUser_bulanan =  $query_bulanan->count();
        $totalHarga_aktivasi = $query_bulanan->sum('registrasi');
        $totalUser_aktivasi = $query_bulanan->count();
        $totalPaket_Bulanan = $query_bulanan->sum('harga_paket');
        $totalUserPaket_bulanan = $query_bulanan->count();

        $inventory = Inventory::query();
        $totalBiaya_Inventory = $query_bulanan->sum('total_biaya');
        $totalUser_Inventory = $query_bulanan->count();

        $rekap_pemasangan = $query->paginate(50);

        return view('rekap_pemasangan.index', compact(
            'query_semua',
            'inventory',
            'query',
            'rekap_pemasangan',
            'totalBiaya',
            'totalUser_bulanan',
            'totalHarga_aktivasi',
            'totalUser_aktivasi',
            'totalPaket_Bulanan',
            'totalUserPaket_bulanan',
            'query_bulanan',
            'totalUser_Inventory',
            'totalBiaya_Inventory',
        ));
    }

    public function create_awal()
    {
        $modems = Modem::whereNull('user')->get(); // Hanya modem yang belum digunakan
        return view('rekap_pemasangan.create', compact('modems'));
    }



    public function show($id)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id);

        // Decode inventory_keluar jika masih dalam format JSON
        $inventory_keluar = json_decode($rekap_pemasangan->inventory_keluar, true);

        return view('rekap_pemasangan.show', compact('rekap_pemasangan', 'inventory_keluar'));
    }


    public function print_psb($id)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id);

        $inventory_keluar = json_decode($rekap_pemasangan->inventory_keluar, true);
        return view('rekap_pemasangan.print', compact('rekap_pemasangan', 'inventory_keluar'));
    }




    public function create()
    {
        $modems = Modem::whereNull('user')->get(); // Hanya modem yang belum digunakan
        $teknisi = X100c::orderBy('nama')->distinct()->get(['nama']);
        $pelanggan = Pelanggan::select('id_plg', 'nama_plg', 'alamat_plg', 'no_telepon_plg', 'paket_plg', 'odp', 'maps')
            ->get();

        $inventory = Inventory::select('nm_brg', 'jml_brg', 'satuan', 'harga_satuan', 'kategori')
            ->where('jml_brg', '>', 0) // Hanya ambil barang yang jumlahnya lebih dari 0
            ->get();

        $odps = DB::table('odp')->select('kecamatan', 'desa', 'dusun', 'kode_odp', 'no_urut_odp', 'jml_port')
            ->orderBy('kecamatan')
            ->get();

        $botTokens = BotToken::all();

        $branch_cabang = BranchCabangModel::all();

        return view('rekap_pemasangan.create', compact('teknisi', 'pelanggan', 'inventory', 'modems', 'odps', 'botTokens', 'branch_cabang'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telpon' => 'required|string',
            'paket_plg' => 'required|string',
            'harga_paket' => 'required|numeric',
            'tgl_pengajuan' => 'required|date',
            'tgl_aktivasi' => 'required|date',
            'sn_modem' => 'nullable|string',
            'registrasi' => 'required|string',
            'marketing' => 'nullable|string',
            'keterangan_plg' => 'nullable|string',
            'odp' => 'required|array',
            'odp.*' => 'required|string',
            'admin' => 'nullable|string',

            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
            'maps' => 'nullable|string',
            'teknisi' => 'nullable|array', // Pastikan teknisi dikirim sebagai array
            'inventory' => 'nullable|array', // Pastikan inventory dikirim sebagai array
            'kt_plg' => 'required|string',
            'kode_cabang' => 'required|string',

        ]);
        // Simpan dalam bentuk array
        $odpData = [
            'kecamatan' => $request->kecamatan,
            'desa' => $request->desa,
            'dusun' => $request->dusun,
            'kode_odp' => $request->kode_odp,
            'no_urut_odp' => $request->no_urut_odp,
        ];
        $admin = Auth::user() ? Auth::user()->name : 'Unknown Admin';
        // Kode perusahaan otomatis
        $kode_perusahaan = '9961';
        // Membuat kode_unik dengan format yang diinginkan
        $kodeUnik = $request->cabang . $kode_perusahaan .
            substr($request->nik, 8, 4) .
            mt_rand(1000, 9999) .      // ← 4 angka acak
            $request->paket_plg;

        // Buat instance baru RekapPemasanganModel
        $rekap_pemasangan = new RekapPemasanganModel();
        $rekap_pemasangan->nik = $request->nik;
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket;
        $rekap_pemasangan->jt = Carbon::parse($request->tgl_aktivasi)->format('d');
        $rekap_pemasangan->status = 'Proses';
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan;
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;
        $rekap_pemasangan->keterangan_plg = $request->keterangan_plg;
        $rekap_pemasangan->id_plg = $kodeUnik;
        // $rekap_pemasangan->odp = $request->odp;
        $rekap_pemasangan->longitude = $request->longitude;
        $rekap_pemasangan->latitude = $request->latitude;
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->sn_modem = $request->sn_modem;
        $rekap_pemasangan->maps = $request->maps;
        $rekap_pemasangan->admin = $admin;
        $rekap_pemasangan->kt_plg = $request->kt_plg;
        $rekap_pemasangan->kode_cabang = $request->kode_cabang;
        // $rekap_pemasangan->odp = json_encode($request->odp2);
        $rekap_pemasangan->odp = json_encode($request->odp);
        $rekap_pemasangan->biaya = intval(300000); // Pastikan sebagai angka
        // Simpan teknisi sebagai string (contoh: "Deden, Agisdut, Dindin")
        $rekap_pemasangan->teknisi = $request->teknisi ? implode(', ', $request->teknisi) : null;
        // Simpan inventory sebagai JSON agar bisa diproses lebih fleksibel
        $rekap_pemasangan->inventory_keluar = $request->inventory ? json_encode($request->inventory) : null;
        $total_biaya = 0;
        if ($request->inventory) {
            foreach ($request->inventory as $item) {
                $total_biaya += $item['jml_brg'] * $item['harga_satuan'];
            }
        }
        $rekap_pemasangan->total_biaya = $total_biaya;
        // Simpan data ke database
        $rekap_pemasangan->save();
        // Perbarui data modem jika ada SN Modem
        if ($request->sn_modem) {
            $modem = Modem::where('sn_modem', $request->sn_modem)->first();
            if ($modem) {
                $modem->user = $request->nama;
                $modem->tgl_keluar = $rekap_pemasangan->created_at;
                $modem->save();
            }
        }
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
                    'nm_brg' => $nm_brg,
                    'jml_brg' => $inv['jml_brg'],
                    'harga_satuan' => $inv['harga_satuan'],
                    'rekap_pemasangan_id' => $rekap_pemasangan->id,
                    'perbaikan_id' => $inv['perbaikan_id'] ?? null,  // Izinkan NULL
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

        // Buat log activity setelah penyimpanan data
        $logData = [
            'id_plg' => $rekap_pemasangan->id_plg,
            'nama_plg' => $rekap_pemasangan->nama,
            'alamat' => $rekap_pemasangan->alamat,
            'cabang' => $rekap_pemasangan->cabang,
            'no_telpon' => $rekap_pemasangan->no_telpon,
            'paket_plg' => $rekap_pemasangan->paket_plg,
            'harga_paket' => $rekap_pemasangan->harga_paket,
            'tgl_pengajuan' => $rekap_pemasangan->tgl_pengajuan,
            'tgl_aktivasi' => $rekap_pemasangan->tgl_aktivasi,
            'sn_modem' => $rekap_pemasangan->sn_modem,
            'registrasi' => $rekap_pemasangan->registrasi,
            'marketing' => $rekap_pemasangan->marketing,
            'keterangan_plg' => $rekap_pemasangan->keterangan_plg,
            'odp' => $rekap_pemasangan->odp,
            'longitude' => $rekap_pemasangan->longitude,
            'latitude' => $rekap_pemasangan->latitude,
            'maps' => $rekap_pemasangan->maps,
            'admin' => $rekap_pemasangan->admin,
            'kt_plg' => $rekap_pemasangan->kt_plg,
            'teknisi' => $rekap_pemasangan->teknisi,
            'inventory_keluar' => $rekap_pemasangan->inventory_keluar,
            'total_biaya' => $rekap_pemasangan->total_biaya,
            'updated_by' => Auth::user()->name ?? 'Guest',
        ];

        logActivity('Tambah data pelanggan baru', 'Pelanggan', $logData);


        $this->sendMessageToCustomer($rekap_pemasangan, $request->token_id);
        $this->sendTelegramNotification($rekap_pemasangan);



        return redirect()->route('rekap_pemasangan.index')->with('success', 'Data rekap pemasangan berhasil disimpan.');
    }



    private function sendMessageToCustomer($rekap_pemasangan, $token_id)
    {
        // Ambil token berdasarkan ID
        $botToken = BotToken::find($token_id);
        if (!$botToken) {
            return back()->withErrors('Token tidak ditemukan.');
        }

        $token = $botToken->token;
        $nama = $rekap_pemasangan->nama;

        // Ambil informasi rekap_pemasangan
        $rekap_pemasangan = RekapPemasanganModel::where('nik', $rekap_pemasangan->nik)->first();
        if (!$rekap_pemasangan) {
            return back()->withErrors('Pelanggan tidak ditemukan.');
        }

        // Hitung tanggal jatuh tempo
        $tglTagihPlg = now()->setDay($rekap_pemasangan->jt);
        $formattedDate = $tglTagihPlg->format('d F Y');

        // Tentukan jenis paket
        $paket = match ($rekap_pemasangan->paket_plg) {
            1 => '5 Mbps',
            2 => '10 Mbps',
            3 => '15 Mbps',
            4 => '25 Mbps',
            default => 'Paket Tidak Diketahui',
        };

        // Format pesan
        $message = "*📢 PEMBERITAHUAN PEMASANGAN WIFI BARU 📢*\n\n";
        $message .= "*Assalamualaikum, Bapak/Ibu Pelanggan Net Digital Group,*\n\n";
        $message .= "Tiket Pemasangan Wifi Baru telah berhasil diproses.\n";
        $message .= "Mohon kesediaanya untuk menunggu, teknisi kami akan segera datang untuk pemasangan Wifi dirumah Bapak/Ibu.\n\n";
        $message .= "*Detail Pelanggan*\n";
        $message .= "👤 *Nama:* {$rekap_pemasangan->nama}\n";
        $message .= "🏠 *Alamat:* {$rekap_pemasangan->alamat}\n";
        $message .= "🌐 *Jenis Paket:* {$paket}\n";
        $message .= "📅 *Tanggal Pemasangan:* {$rekap_pemasangan->tgl_aktivasi}\n\n";
        $message .= "Terima kasih telah menggunakan layanan *Net Digital Group*.\n";
        $message .= "🙏 Kami siap membantu Anda kapan saja! 🙌\n\n";

        $message .= "Menghubungkan Negeri, Mewujudkan Mimpi.\n";
        $message .= "🌐 www.netdigitalgroup.com\n";

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $rekap_pemasangan->no_telpon,
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



    private function sendTelegramNotification($rekap_pemasangan)
    {
        $adminName = auth()->user()->name;
        //$token = '7558654529:AAE4GLCbqr5bnFj_P04Ll8KMFUmJ6sxg7aM';
        $token = '';
        $chat_id = '-4743236105';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $message =
            "========================\n" .
            "📣 *Notifikasi Pemasangan Baru*\n" .
            "========================\n" .
            "🆔 *ID Pelanggan :* {$rekap_pemasangan->id_plg}\n" .
            "👤 *Nama :* {$rekap_pemasangan->nama}\n" .
            "📍 *Alamat :* {$rekap_pemasangan->alamat}\n" .
            "🗺️ *Maps :* \n" .
            "📞 *Telepon :* {$rekap_pemasangan->no_telpon}\n" .
            "📦 *Paket :* {$rekap_pemasangan->paket_plg}\n" .
            "🚦 *Keterangan :* {$rekap_pemasangan->keterangan}\n\n" .
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


    public function aktivasi($id)
    {
        // Ambil data rekap pemasangan berdasarkan ID
        $rekapPemasangan = RekapPemasanganModel::find($id);

        if (!$rekapPemasangan) {
            return redirect()->back()->with('error', 'Data pemasangan tidak ditemukan.');
        }

        // Cek apakah pelanggan sudah ada di tabel `pelanggan`
        $existingPelanggan = Pelanggan::where('id_plg', $rekapPemasangan->id_plg)->first();

        if ($existingPelanggan) {
            return redirect()->route('pelanggan.psb')->with('error', 'Pelanggan ini sudah diaktivasi.');
        }

        // Simpan data pelanggan baru
        $pelanggan = new Pelanggan();
        $pelanggan->id_plg = $rekapPemasangan->id_plg;
        $pelanggan->nama_plg = $rekapPemasangan->nama;
        $pelanggan->alamat_plg = $rekapPemasangan->alamat;
        $pelanggan->no_telepon_plg = $rekapPemasangan->no_telpon;
        $pelanggan->paket_plg = $rekapPemasangan->paket_plg;
        $pelanggan->harga_paket = $rekapPemasangan->harga_paket;
        $pelanggan->odp = $rekapPemasangan->odp;
        $pelanggan->longitude = $rekapPemasangan->longitude;
        $pelanggan->latitude = $rekapPemasangan->latitude;
        $pelanggan->aktivasi_plg = $rekapPemasangan->tgl_aktivasi;
        $pelanggan->maps = $rekapPemasangan->maps;
        $pelanggan->kt_plg = $rekapPemasangan->kt_plg;
        $pelanggan->cabang = $rekapPemasangan->cabang;


        // Mengambil tanggal saja dari tanggal aktivasi
        //
        if ($rekapPemasangan->tgl_aktivasi) {
            $pelanggan->tgl_tagih_plg = Carbon::parse($rekapPemasangan->tgl_aktivasi)->format('d');
        } else {
            $pelanggan->tgl_tagih_plg = Carbon::now()->format('d'); // Default ke tanggal hari ini jika null
        }

        $pelanggan->status_pembayaran = 'PSB'; // Status awal PSB
        $pelanggan->save();

        $this->aktivasi_biaya($id);
        $this->aktivasi_pemasukan($id);
        $this->selesai($id);

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Pelanggan berhasil diaktivasi.');
    }


    public function aktivasi_biaya($id)
    {
        // Ambil data rekap pemasangan berdasarkan ID
        $rekapPemasangan = RekapPemasanganModel::find($id);

        if (!$rekapPemasangan) {
            return redirect()->back()->with('error', 'Data pemasangan tidak ditemukan.');
        }

        // Simpan data ke tabel pengeluaran
        $pengeluaran = new PengeluaranModel();
        $pengeluaran->harga_total = '300000'; // Sesuai dengan biaya pemasangan
        $pengeluaran->kategori = 'Pemasangan';
        $pengeluaran->deskripsi = 'PSB ' . $rekapPemasangan->nama;
        $pengeluaran->volume = 1;
        $pengeluaran->harga_satuan = '300000'; // Ambil dari biaya pemasangan
        $pengeluaran->keterangan = 'Pengeluaran untuk pemasangan Baru pelanggan ' . $rekapPemasangan->nama;

        $pengeluaran->save();
    }


    public function aktivasi_pemasukan($id)
    {
        // Ambil data rekap pemasangan berdasarkan ID
        $rekapPemasangan = RekapPemasanganModel::find($id);

        if (!$rekapPemasangan) {
            return redirect()->back()->with('error', 'Data pemasangan tidak ditemukan.');
        }

        // Simpan data ke tabel pengeluaran
        $pengeluaran = new PemasukanModel();
        $pengeluaran->harga_total = $rekapPemasangan->registrasi;
        $pengeluaran->kategori = 'Pemasangan';
        $pengeluaran->deskripsi = 'Registrasi ' . $rekapPemasangan->nama;
        $pengeluaran->volume = 1;
        $pengeluaran->harga_satuan =  $rekapPemasangan->registrasi;
        $pengeluaran->keterangan = 'Pemasukan Registrasi PSB ' . $rekapPemasangan->nama;

        $pengeluaran->save();
    }


    public function selesai($id)
    {
        $psb = RekapPemasanganModel::findOrFail($id);
        $psb->status = 'selesai'; // Ubah status menjadi 'selesai'
        $psb->save();

        return back()->with('success', 'Perbaikan telah ditandai selesai');
    }


    public function update_status($id)
    {
        $rekap_pemasangan = RekapPemasanganModel::find($id);
        $rekap_pemasangan->status = 'Open';

        $rekap_pemasangan->save();
    }

    public function edit(string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);

        $modems = Modem::whereNull('user')->get(); // Hanya modem yang belum digunakan
        return view('rekap_pemasangan.edit', compact('rekap_pemasangan', 'modems'));
    }


    public function update(Request $request, string $id_plg)
    {
        $request->validate([
            'sn_modem_baru' => 'nullable|string|max:255',
            'sn_modem' => 'nullable|string|max:255',
        ]);

        // Cari data berdasarkan id pelanggan
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);

        // Cek apakah input SN Modem Baru diisi atau tidak
        $rekap_pemasangan->sn_modem = $request->sn_modem_baru ? $request->sn_modem_baru : $request->sn_modem;

        // Update data lainnya
        $rekap_pemasangan->id_plg = $request->id_plg;
        $rekap_pemasangan->nik = $request->nik;
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon;
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket;
        $rekap_pemasangan->jt = $request->jt;
        $rekap_pemasangan->status = $request->status;
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan;
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;
        $rekap_pemasangan->teknisi = $request->teknisi ? implode(', ', $request->teknisi) : null;

        // Simpan perubahan ke database
        $rekap_pemasangan->save();

        return redirect()->back()->with('success', 'Data rekap pemasangan berhasil disimpan.');
    }


    public function destroy(string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);
        $rekap_pemasangan->delete();

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Data pemasangan berhasil Di Hapus, Atas Nama : ' . $rekap_pemasangan->nama . '.');
    }

    public function updateTglTagihPlg()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {

            if ($pelanggan->tgl_tagih_plg == ' jadi tanggal sekarang') {
            }
        }
    }
}

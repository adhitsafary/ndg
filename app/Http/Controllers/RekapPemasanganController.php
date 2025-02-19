<?php

namespace App\Http\Controllers;

use App\Models\GeneratorId;
use App\Models\KaryawanModel;
use App\Models\KasbonModel;
use App\Models\Modem;
use App\Models\Netnet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\RekapPemasanganModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $query = RekapPemasanganModel::query();

        $query->orderBy('created_at', 'desc');

        $paket_plg = $request->input('paket_plg');
        $paket_plg = $request->input('nama');
        $paket_plg = $request->input('marketing');
        $nominal = $request->input('nominal');
        $tgl_pengajuan = $request->input('tgl_pengajuan');
        $tgl_aktivasi = $request->input('tgl_aktivasi');
        $registrasi = $request->input('registrasi'); // Filter jumlah pembayaran

        // Filter tanggal created_at
        if ($request->filled('created_at_dari')) {
            $query->whereDate('created_at', '>=', $request->created_at_dari);
        }

        if ($request->filled('created_at_sampai')) {
            $query->whereDate('created_at', '<=', $request->created_at_sampai);
        }

        // Filter berdasarkan tanggal tagih
        if ($tgl_pengajuan) {
            $query->where('tgl_pengajuan', $tgl_pengajuan);
        }

        if ($registrasi) {
            $query->where('registrasi', $registrasi);
        }


        if ($tgl_aktivasi) {
            $query->whereDate('tgl_aktivasi', $tgl_aktivasi);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($nominal) {
            $query->where('nominal', $nominal);
        }

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('marketing', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('no_telpon', 'like', "%{$search}%")
                    ->orWhere('registrasi', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $rekap_pemasangan = $query->get();


        return view('rekap_pemasangan.index', compact('rekap_pemasangan'));
    }

    public function create()
    {
        $modems = Modem::whereNull('user')->get(); // Hanya modem yang belum digunakan
        return view('rekap_pemasangan.create', compact('modems'));
    }

    public function store_awal(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_plg' => 'required|unique:rekap_pemasangan,id_plg',
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
            'odp' => 'nullable|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
        ]);

        // Kode perusahaan otomatis
        $kode_perusahaan = '9961';

        // Buat instance baru RekapPemasanganModel
        $rekap_pemasangan = new RekapPemasanganModel();
        $rekap_pemasangan->nik = $request->nik;
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket;
        $rekap_pemasangan->jt = Carbon::parse($request->tgl_aktivasi)->format('d');
        $rekap_pemasangan->status = 'Open';
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan;
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;
        $rekap_pemasangan->keterangan_plg = $request->keterangan_plg;
        $rekap_pemasangan->id_plg = $request->id_plg;
        $rekap_pemasangan->odp = $request->odp;
        $rekap_pemasangan->longitude = $request->longitude;
        $rekap_pemasangan->latitude = $request->latitude;
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->sn_modem = $request->sn_modem;

        // Simpan data rekap_pemasangan ke database
        $rekap_pemasangan->save();

        // Simpan data GeneratorId setelah rekap_pemasangan disimpan
        // Membuat data GeneratorId baru
        $generatorId = new GeneratorId();
        $generatorId->kode_perusahaan = $kode_perusahaan;
        $generatorId->kode_nik = $rekap_pemasangan->nik;  // Mengambil NIK dari data rekap_pemasangan
        $generatorId->kode_odp = $rekap_pemasangan->odp;  // Mengambil ODP dari data rekap_pemasangan
        $generatorId->kode_paket_plg = $rekap_pemasangan->paket_plg; // Mengambil paket_plg dari data rekap_pemasangan

        // Simpan data GeneratorId yang baru
        $generatorId->save();

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Data rekap pemasangan berhasil disimpan.');
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
            'odp' => 'nullable|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
            'maps' => 'nullable|string',
        ]);

        // Kode perusahaan otomatis
        $kode_perusahaan = '9961';

        // Membuat kode_unik dengan format yang diinginkan
        $kodeUnik = $kode_perusahaan .
            substr($request->nik, 8, 4) .
            substr($request->odp, 0, 3) .
            $request->paket_plg .
            '';

        // Buat instance baru RekapPemasanganModel
        $rekap_pemasangan = new RekapPemasanganModel();
        $rekap_pemasangan->nik = $request->nik;
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket;
        $rekap_pemasangan->jt = Carbon::parse($request->tgl_aktivasi)->format('d');
        $rekap_pemasangan->status = 'Open';
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan;
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;
        $rekap_pemasangan->keterangan_plg = $request->keterangan_plg;
        $rekap_pemasangan->id_plg = $kodeUnik;
        $rekap_pemasangan->odp = $request->odp;
        $rekap_pemasangan->longitude = $request->longitude;
        $rekap_pemasangan->latitude = $request->latitude;
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->sn_modem = $request->sn_modem;
        $rekap_pemasangan->maps = $request->maps;

        // Simpan data rekap_pemasangan ke database
        $rekap_pemasangan->save();

        $this->sendMessageToCustomer($rekap_pemasangan);

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($rekap_pemasangan);


        // Perbarui user dan tgl_keluar pada tabel modem jika sn_modem disediakan
        if ($request->sn_modem) {
            $modem = Modem::where('sn_modem', $request->sn_modem)->first();
            if ($modem) {
                $modem->user = $request->nama;
                $modem->tgl_keluar = $rekap_pemasangan->created_at;
                $modem->save();
            }
        }

        // Menambahkan data GeneratorId
        // $generatorId = new GeneratorId();
        // $generatorId->kode_perusahaan = $kode_perusahaan;
        // $generatorId->kode_nik = $rekap_pemasangan->nik;
        // $generatorId->kode_odp = $rekap_pemasangan->odp;
        // $generatorId->kode_paket_plg = $rekap_pemasangan->paket_plg;
        // $generatorId->kode_unik = $kodeUnik;
        // $generatorId->id_plg = $request->id_plg;

        // Simpan data GeneratorId yang baru
        // $generatorId->save();

        // Update data pelanggan dengan kode_unik dan kode_nik
        // $pelanggan = Pelanggan::find($request->id_plg);
        //  if ($pelanggan) {
        //    $pelanggan->update([
        //        'kode_unik' => $kodeUnik,
        //        'nik' => $request->nik,
        //    ]);
        // }



        return redirect()->route('rekap_pemasangan.index')->with('success', 'Data rekap pemasangan dan Generator ID berhasil disimpan.');
    }


    private function sendMessageToCustomer($rekap_pemasangan)
    {
        $token = "uPQuNAPZ2docn9iMxz9Y"; // Ganti dengan token yang sesuai
        $nama = $rekap_pemasangan->nama;


        // Ambil informasi rekap_pemasangan
        $rekap_pemasangan = RekapPemasanganModel::where('nik', $rekap_pemasangan->nik)->first();
        if (!$rekap_pemasangan) {
            return back()->withErrors('Pelanggan tidak ditemukan.');
        }

        // Hitung tanggal jatuh tempo
        $tglTagihPlg = now()->setDay($rekap_pemasangan->tgl_aktivasi);
        $formattedDate = $tglTagihPlg->format('d F Y');

        // Tentukan jenis paket berdasarkan nilai paket_plg
        $paket = match ($rekap_pemasangan->paket_plg) {
            1 => '5 Mbps',
            2 => '10 Mbps',
            3 => '15 Mbps',
            4 => '25 Mbps',
            default => "default",
        };


        // Format pesan yang akan dikirim
        $message = "*📢 PEMBERITAHUAN PEMASANGAN BARU 📢*\n\n";
        $message .= "*Assalamualaikum, Bapak/Ibu Pelanggan Net Digital Group,*\n\n";
        $message .= "Tiket Pemasangan Baru telah berhasil diproses.\n";
        $message .= "Mohon ditunggu, teknisi kami akan segera datang untuk Pemasangan Wifi dirumah Bapak/Ibu.\n\n";
        $message .= "*🔹 Detail Pelanggan 🔹*\n";
        $message .= "👤 *Nama:* {$rekap_pemasangan->nama}\n";
        $message .= "🏠 *Alamat:* {$rekap_pemasangan->alamat}\n";
        $message .= "🌐 *Jenis Paket:* {$paket}\n";
        $message .= "📅 *Tanggal Pemasangan* {$rekap_pemasangan->tgl_aktivasi}\n\n";
        $message .= "Terima kasih atas kepercayaan Anda menggunakan layanan *Net Digital Group*.\n\n";
        $message .= "🙏 Kami siap membantu Anda kapan saja! 🙌\n";

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

        $token = '7558654529:AAE4GLCbqr5bnFj_P04Ll8KMFUmJ6sxg7aM';
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








    public function aktivasi2($id)
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
        $pelanggan->aktivasi_plg = $rekapPemasangan->tgl_aktivasi;
        $pelanggan->latitude = $rekapPemasangan->latitude;
        $pelanggan->tgl_tagih_plg = \Carbon\Carbon::now()->format('d'); // Tagih di hari ini
        // $pelanggan->tgl_tagih_plg = \Carbon\Carbon::parse($rekapPemasangan->tgl_aktivasi)->format('d'); //ini tgl tagih pelanggan
        $pelanggan->status_pembayaran = 'PSB'; // Status awal PSB

        $pelanggan->save();

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Pelanggan berhasil diaktivasi.');
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

        // Mengambil tanggal saja dari tanggal aktivasi
        if ($rekapPemasangan->tgl_aktivasi) {
            $pelanggan->tgl_tagih_plg = Carbon::parse($rekapPemasangan->tgl_aktivasi)->format('d');
        } else {
            $pelanggan->tgl_tagih_plg = Carbon::now()->format('d'); // Default ke tanggal hari ini jika null
        }

        $pelanggan->status_pembayaran = 'PSB'; // Status awal PSB
        $pelanggan->save();

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Pelanggan berhasil diaktivasi.');
    }



    public function show(string $id) {}


    public function edit(string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);
        return view('rekap_pemasangan.edit', compact('rekap_pemasangan'));
    }


    public function update(Request $request, string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);

        $rekap_pemasangan->id_plg = $request->id_plg;
        $rekap_pemasangan->nik = $request->nik; // Nama dari form input
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon; // Nama dari form input
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket; // Nama dari form input
        $rekap_pemasangan->jt = $request->jt;
        $rekap_pemasangan->status = $request->status;
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan; // Nama dari form input
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;
        $rekap_pemasangan->sn_modem = $request->sn_modem;


        $rekap_pemasangan->save();

        return redirect()->route('rekap_pemasangan.index');
    }


    public function destroy(string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);
        $rekap_pemasangan->delete();

        return redirect()->route('rekap_pemasangan.index');
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

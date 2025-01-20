<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function create2(Request $request)
    {
        //$query = Pelanggan::query();

        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid']);


        // Menambahkan filter berdasarkan input dari pengguna
        if ($request->filled('search')) {
            $query->where('nama_plg', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('alamat_plg')) {
            $query->where('alamat_plg', 'like', '%' . $request->alamat_plg . '%');
        }

        if ($request->filled('tgl_tagih_plg')) {
            $query->where('tgl_tagih_plg', $request->tgl_tagih_plg);
        }

        // Ambil data pelanggan yang telah difilter
        $pelanggan = $query->get(['id_plg', 'nama_plg', 'no_telepon_plg', 'tgl_tagih_plg']);

        return view('whatsapp.send-message', compact('pelanggan'));
    }

    public function create(Request $request)
    {
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid']);

        // Filter pelanggan
        if ($request->filled('search')) {
            $query->where('nama_plg', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('alamat_plg')) {
            $query->where('alamat_plg', 'like', '%' . $request->alamat_plg . '%');
        }

        if ($request->filled('tgl_tagih_plg')) {
            $query->where('tgl_tagih_plg', $request->tgl_tagih_plg);
        }

        $pelanggan = $query->get(['id_plg', 'nama_plg', 'no_telepon_plg', 'tgl_tagih_plg']);

        // Ambil data token dari tabel bot_tokens
        $botTokens = DB::table('bot_tokens')->get(['id', 'name', 'token']);

        return view('whatsapp.send-message', compact('pelanggan', 'botTokens'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'target' => 'required|array',
            'token_id' => 'required|exists:bot_tokens,id',
        ]);

        // Ambil token dari tabel berdasarkan token_id
        $tokenData = DB::table('bot_tokens')->find($request->token_id);
        $token = $tokenData->token;

        // Ambil daftar target
        $targetNumbers = $request->input('target');
        $errors = []; // Menyimpan pesan error jika terjadi kegagalan

        foreach ($targetNumbers as $target) {
            try {
                // Ambil data pelanggan berdasarkan nomor telepon
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if (!$pelanggan) {
                    $errors[] = "Pelanggan dengan nomor {$target} tidak ditemukan.";
                    continue;
                }

                // Konversi tanggal tagihan ke format yang sesuai
                $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                $formattedDate = $tglTagihPlg->format('d F Y');

                // Tentukan jenis paket
                $paketList = [
                    1 => '5 Mbps',
                    2 => '10 Mbps',
                    3 => '15 Mbps',
                    4 => '25 Mbps',
                ];
                $paket = $paketList[$pelanggan->paket_plg] ?? 'Paket tidak diketahui';

                // Siapkan pesan
                $message = $this->generateMessage($pelanggan, $formattedDate, $paket);

                // Kirim pesan menggunakan API
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'delay' => '5',
                ]);

                // Cek respon API
                if (!$response->successful()) {
                    $errors[] = "Gagal mengirim pesan ke {$target}: " . $response->body();
                }
            } catch (\Exception $e) {
                $errors[] = "Terjadi kesalahan pada nomor {$target}: " . $e->getMessage();
            }
        }

        // Tampilkan hasil
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        return back()->with('status', 'Pesan berhasil dikirim target!');
    }

    /**
     * Generate pesan WhatsApp
     */
    private function generateMessage($pelanggan, $formattedDate, $paket)
    {
        $message = "*REMINDER🙏🏻*\n";
        $message .= "*NET | NET. DIGITAL-WiFi*\n\n";
        $message .= "*Pelanggan YTH:*\n";
        $message .= "*{$pelanggan->nama_plg} - {$pelanggan->alamat_plg}*\n\n";
        $message .= "*PEMBERITAHUAN*\n";
        $message .= "Tagihan Bulan : " . now()->format('F Y') . "\n";
        $message .= "Jenis Paket : {$paket}\n";
        $message .= "Biaya Paket : Rp. {$pelanggan->harga_paket}\n";
        $message .= "*Total Besar Tagihan + PPN : Rp. {$pelanggan->harga_paket}*\n";
        $message .= "Masa aktif s/d {$formattedDate}\n";
        $message .= "Ket : *BELUM TERBAYAR*\n\n";
        $message .= "PEMBAYARAN:\n";
        $message .= "- via transfer : rek BCA : 3770198576 atas nama *Ruslandi* \n";
        $message .= "- *Pembayaran Via Penjemputan/Pickup dikenakan biaya jasa pengambilan Rp.5000*\n\n";
        $message .= "Dimohon untuk Melampirkan bukti pembayaran apabila sudah melakukan pembayaran.\n\n";
        $message .= "INFO TAMBAHAN:\n";
        $message .= "*Apabila telat melakukan pembayaran iuran wifi akan dikenakan pemutusan sementara🔊.* \n\n";
        $message .= "Admin + CS     : 0857-9392-0206 (Agisna 🧕🏻)\n";
        $message .= "marketing      : 0857-2222-0169 (Gilang 👳🏻‍♂️)\n";
        $message .= "🙏🏻";

        return $message;
    }



    public function peringatan(Request $request)
    {
        // $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'Block', 'Isolir']);
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'unpaid', 'PSB']);
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid']);

        if ($request->filled('search')) {
            $query->where('nama_plg', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('alamat_plg')) {
            $query->where('alamat_plg', 'like', '%' . $request->alamat_plg . '%');
        }

        if ($request->filled('tgl_tagih_plg')) {
            $query->where('tgl_tagih_plg', $request->tgl_tagih_plg);
        }

        $botTokens = DB::table('bot_tokens')->get(['id', 'name', 'token']);

        $pelanggan = $query->get(['id_plg', 'nama_plg', 'no_telepon_plg', 'tgl_tagih_plg', 'alamat_plg', 'paket_plg']);

        return view('whatsapp.peringatan', compact('pelanggan', 'botTokens'));
    }

    public function store_peringatan(Request $request)
    {
        $request->validate([
            'target' => 'required|array',
            'token_id' => 'required|exists:bot_tokens,id',
        ]);

        $tokenData = DB::table('bot_tokens')->find($request->token_id);
        $token = $tokenData->token;

        $targetNumbers = $request->input('target');

        try {
            foreach ($targetNumbers as $target) {
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if ($pelanggan) {
                    $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                    $formattedDate = $tglTagihPlg->format('d F Y');

                    $paket = match ($pelanggan->paket_plg) {
                        1 => '5 Mbps',
                        2 => '10 Mbps',
                        3 => '15 Mbps',
                        4 => '25 Mbps',
                        default => 'Paket tidak diketahui',
                    };

                    $message = "Assalamualaikum selamat siang. \n";
                    $message .= "Bapak / ibu {$pelanggan->nama_plg}, kami dari net net, untuk menghindari isolir pembayaran bulanan \n";
                    $message .= "nya bisa di bayar hari ini?\n";
                    $message .= "Bisa lewat transfer atau dana .🙏🏻\n";

                    $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $target,
                        'message' => $message,
                        'delay' => '5',
                    ]);

                    if (!$response->successful()) {
                        return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
                    }
                }
            }

            return back()->with('status', 'Pesan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function rayuan(Request $request)
    {
        // $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'Block', 'Isolir']);
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'unpaid', 'PSB']);

        if ($request->filled('search')) {
            $query->where('nama_plg', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('alamat_plg')) {
            $query->where('alamat_plg', 'like', '%' . $request->alamat_plg . '%');
        }

        if ($request->filled('tgl_tagih_plg')) {
            $query->where('tgl_tagih_plg', $request->tgl_tagih_plg);
        }

        $botTokens = DB::table('bot_tokens')->get(['id', 'name', 'token']);

        $pelanggan = $query->get(['id_plg', 'nama_plg', 'no_telepon_plg', 'tgl_tagih_plg', 'alamat_plg', 'paket_plg']);

        return view('whatsapp.rayuan', compact('pelanggan', 'botTokens'));
    }

    public function store_rayuan(Request $request)
    {
        $request->validate([
            'target' => 'required|array',
            'token_id' => 'required|exists:bot_tokens,id',
        ]);

        $tokenData = DB::table('bot_tokens')->find($request->token_id);
        $token = $tokenData->token;

        $targetNumbers = $request->input('target');

        try {
            foreach ($targetNumbers as $target) {
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if ($pelanggan) {
                    $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                    $formattedDate = $tglTagihPlg->format('d F Y');

                    $paket = match ($pelanggan->paket_plg) {
                        1 => '5 Mbps',
                        2 => '10 Mbps',
                        3 => '15 Mbps',
                        4 => '25 Mbps',
                        default => 'Paket tidak diketahui',
                    };

                    $message = "Assalamualaikum selamat siang. \n";
                    $message .= "Bapak/ ibu {$pelanggan->nama_plg} kami dari Provider Wifi net net, untuk pengaktifan nya kembali , demi kenyamanan layanan wifi anda, bisa dengan segera melakukan pembayaran sesuai tagihan yang telah kami kirimkan sebelum nya. \n";
                    $message .= "Bisa lewat transfer via BCA atau dana. Terimakasih🙏 \n";

                    $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $target,
                        'message' => $message,
                        'delay' => '5',
                    ]);

                    if (!$response->successful()) {
                        return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
                    }
                }
            }

            return back()->with('status', 'Pesan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

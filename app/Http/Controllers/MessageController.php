<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pelangganof;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{

    public function create(Request $request)
    {
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'PSB', 'Reactivasi'])->whereNotIn('paket_plg', ['vcr']);

        //  $query = Pelanggan::whereNotIn('paket_plg', 'vcr');

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
        $request->validate([
            'target' => 'required|array',
            'token_id' => 'required|exists:bot_tokens,id',
        ]);

        $tokenData = DB::table('bot_tokens')->find($request->token_id);
        $token = $tokenData->token;

        $targetIds = $request->input('target');
        $pelanggans = Pelanggan::whereIn('id_plg', $targetIds)->get();
        $errors = [];

        foreach ($pelanggans as $pelanggan) {
            try {
                $target = $pelanggan->no_telepon_plg;

                $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                $formattedDate = $tglTagihPlg->format('d F Y');

                $paketList = [
                    1 => '5 Mbps',
                    2 => '10 Mbps',
                    3 => '15 Mbps',
                    4 => '25 Mbps',
                ];
                $paket = $paketList[$pelanggan->paket_plg] ?? 'Paket tidak diketahui';

                $message = $this->generateMessage($pelanggan, $formattedDate, $paket);

                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'delay' => '5',
                ]);

                if ($response->successful()) {
                    logActivity('Kirim pesan tagihan', 'whatsapp', [
                        'ID' => $pelanggan->id_plg,
                        'nama' => $pelanggan->nama_plg,
                        'no telepon' => $pelanggan->no_telepon_plg,
                        'pesan' => $message,
                        'oleh' => Auth::user()->name ?? 'Guest',
                    ]);
                } else {
                    $errors[] = "Gagal mengirim pesan ke {$target}: " . $response->body();
                }
            } catch (\Exception $e) {
                $errors[] = "Terjadi kesalahan pada nomor {$target}: " . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        return back()->with('status', 'Pesan berhasil dikirim target!');
    }



    private function generateMessage($pelanggan, $formattedDate, $paket)
    {
        $message = "Bot Pemberitahuan🙏🏻\n";
        $message .= "Net Digital Group\n\n";
        $message .= "Pelanggan YTH:\n";
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
        $message .= "*Apabila sudah melakukan pembayaran, mohon untuk Melampirkan bukti pembayaran dan mencantumkan nama pengirim nya🙏.*\n\n";
        $message .= "INFO TAMBAHAN:\n";
        $message .= "*Apabila telat melakukan pembayaran wifi maka akan dikenakan pemutusan sementara🔊.* \n\n";
        $message .= "Admin   : 0857-9392-0206 (Agisna 🧕🏻)\n";
        $message .= "CS      : 0857-2222-0169 (Gilang 👳🏻‍♂️)\n\n";
        $message .= "kunjungi website kami di www.netdigitalgroup.com \n";
        return $message;
    }


    public function peringatan(Request $request)
    {
        // $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'Block', 'Isolir']);
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'PSB', 'Reactivasi']);

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

        $targetIds = $request->input('target');
        $pelanggans = Pelanggan::whereIn('id_plg', $targetIds)->get();

        try {
            foreach ($pelanggans as $pelanggan) {
                $target = $pelanggan->no_telepon_plg;

                $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                $formattedDate = $tglTagihPlg->format('d F Y');

                $bulanTagihan = now()->translatedFormat('F Y');

                $paket = match ($pelanggan->paket_plg) {
                    1 => '5 Mbps',
                    2 => '10 Mbps',
                    3 => '15 Mbps',
                    4 => '25 Mbps',
                    default => 'Paket tidak diketahui',
                };

                $message = "‼️ *INFORMASI PENTING*\n\n";
                $message .= "Pelanggan Net Digital Group Yth. 👋🏻\n";
                $message .= "*{$pelanggan->nama_plg} - {$pelanggan->alamat_plg}.*\n\n";
                $message .= "Pesan ini mengingatkan *kewajiban tagihan Wifi* Bapak/Ibu untuk *bulan {$bulanTagihan}* yang saat ini berstatus *Belum Lunas*.\n";
                $message .= "_Abaikan pesan ini jika Bapak/Ibu telah melakukan pembayaran._\n\n";
                $message .= "Sebagaimana sudah diinfokan sebelumnya, *periode pembayaran tagihan bulanan* adalah *paling lambat sesuai tanggal tagih setiap bulannya*.\n";
                $message .= "Jika sampai melewati tanggal tersebut tanpa konfirmasi, maka *dengan berat hati layanan akan kami nonaktifkan sementara*.\n\n";
                $message .= "Layanan akan kembali diaktifkan secara otomatis setelah status tagihan menjadi *Lunas*.\n\n";
                $message .= "Demikian informasi tagihan ini kami sampaikan.\n";
                $message .= "Atas perhatian dan kerja samanya, kami ucapkan terima kasih.\n\n";
                $message .= "Salam,\nAdmin Net Digital Group";

                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'delay' => '5',
                ]);

                if ($response->successful()) {
                    if (!function_exists('logActivity')) {
                        function logActivity($activity, $model, $data = [])
                        {
                            DB::table('log_activity')->insert([
                                'activity' => $activity,
                                'model' => $model,
                                'data' => json_encode($data),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }

                    logActivity('Kirim pesan Reminder', 'whatsapp', [
                        'ID' => $pelanggan->id_plg,
                        'nama' => $pelanggan->nama_plg,
                        'no telepon' => $pelanggan->no_telepon_plg,
                        'pesan' => $message,
                        'oleh' => Auth::user()->name ?? 'Guest',
                    ]);
                } else {
                    return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
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
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'PSB', 'Reactivasi']);

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

        $targetIds = $request->input('target');
        $pelanggans = Pelanggan::whereIn('id_plg', $targetIds)->get();

        try {
            foreach ($pelanggans as $pelanggan) {
                $target = $pelanggan->no_telepon_plg;

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
                    $message .= "Bapak / ibu yang terhormat {$pelanggan->nama_plg} kami dari Provider Wifi Net Digital Group, demi kenyamanan layanan wifi anda, bisa dengan segera melakukan pembayaran sesuai tagihan yang telah kami kirimkan sebelum nya. \n";
                    $message .= "Bisa lewat transfer via BCA atau dana, dan Apabila sudah melakukan pembayaran, mohon untuk Melampirkan bukti pembayaran dan mencantumkan nama pengirim nya.\n";
                    $message .= "Terimakasih🙏 \n\n";


                    $message .= "kunjungi website kami di www.netdigitalgroup.com \n";

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

    public function perhatian(Request $request)
    {
        // $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'Block', 'Isolir']);
        $query = Pelanggan::whereNotIn('status_pembayaran', ['n']);

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

        return view('whatsapp.perhatian', compact('pelanggan', 'botTokens'));
    }

    public function store_perhatian(Request $request)
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

                    $message = "*Assalamualaikum selamat siang.* \n\n";
                    $message .= "Bapak/ ibu *{$pelanggan->nama_plg}* Mohon Maaf Mengganggu, kami dari Provider Wifi Net Net, Mohon Perhatianya bila ada Ada oknum yang ingin *MENGAMBIL* Modem dengan alasan pergantian Unit baru atau apapun itu dengan Mengatasnamakan kami. \n";
                    $message .= "Harap dikonfirmasi dulu ke Nomer ini atau Admin. Terimakasih🙏 \n";
                    $message .= "Admin   : 0857-9392-0206 (Agisna 🧕🏻)\n";
                    $message .= "CS      : 0857-2222-0169 (Gilang 👳🏻‍♂️)\n";
                    $message .= "Info Pemasangan    : 0821-2385-2983 (Adit 👳🏻‍♂️)\n";

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



    public function tiara(Request $request)
    {
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'PSB', 'Reactivasi']);
        //  $query = Pelanggan::whereNotIn('paket_plg', 'vcr');

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

        return view('whatsapp.tiara', compact('pelanggan', 'botTokens'));
    }

    public function store_tiara(Request $request)
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

                    $message = "*Pelanggan Tiara Net Yth👋👋* \n\n";
                    $message .= "Halo Bapak/Ibu *{$pelanggan->nama_plg}* - *{$pelanggan->alamat_plg}*, semoga hari Anda menyenangkan. 😊\n\n";
                    $message .= "Kami ingin mengingatkan bahwa pembayaran pemasangan WiFi Anda telah mencapai batas waktu yang disepakati, yaitu *1 minggu setelah pemasangan*.\n\n";
                    $message .= "🔹 *Nama Pelanggan:* {$pelanggan->nama_plg}\n";
                    $message .= "🔹 *Tanggal Aktivasi:* {$pelanggan->aktivasi_plg}\n";
                    $message .= "🔹 *Biaya Pemasangan:* Rp. " . number_format($pelanggan->harga_paket, 0, ',', '.') . "\n";
                    $message .= "🔹 *Status:* Belum Dibayar\n\n";
                    $message .= "💳 *Metode Bayar:*\n";
                    $message .= "✅ *Via transfer:* rek BCA : 3770198576 a.n Ruslandi\n";
                    $message .= "✅ *Pick-Up/Penjemputan* oleh petugas penagihan\n\n";
                    $message .= "Mohon segera melakukan pembayaran agar tidak terjadi *pemutusan layanan internet* Anda. Jika sudah melakukan pembayaran, mohon konfirmasi kepada kami.\n\n";
                    $message .= "Terima kasih atas kerja sama dan kepercayaan Anda menggunakan layanan kami. Jika ada kendala atau pertanyaan, jangan ragu untuk menghubungi kami. 😊🙏\n\n";
                    $message .= "📞 *Admin* : 0857-9392-0206 (*Agisna* 🧕🏻)\n";

                    $message .= "*TIARANET - Dari & Untuk Warga Tiara*";


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




    public function bayar25(Request $request)
    {
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'PSB', 'Reactivasi']);
        //  $query = Pelanggan::whereNotIn('paket_plg', 'vcr');

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

        return view('whatsapp.bayar25', compact('pelanggan', 'botTokens'));
    }




    public function store_bayar25_2(Request $request)
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

                    $message = "✨🌙 *SPESIAL RAMADHAN! PENAWARAN ISTIMEWA UNTUK PELANGGAN SETIA WIFI NDG!* 🎁💰\n\n";
                    $message .= "🌙🌟 *Marhaban ya Ramadhan* 🌟🌙\n";
                    $message .= "Pelanggan Terhormat,\nBapak/Ibu *{$pelanggan->nama_plg}*,\n\n";
                    $message .= "Di bulan penuh berkah ini, Net Digital Group menghadirkan *promo spesial* untuk Anda! Jangan lewatkan kesempatan mendapatkan *kupon hadiah langsung* dengan membayar tagihan internet *sebelum tanggal 25* di kantor kami! 🎉\n\n";
                    $message .= "💡 *Syarat mudah:*\n";
                    $message .= "✅ Bayar langsung ke kantor sebelum tanggal 25\n";
                    $message .= "✅ Dapatkan *kupon hadiah* yang bisa ditukar dengan hadiah menarik 🎁\n\n";
                    $message .= "🎊 *Bonus Spesial Ramadhan! Dapatkan Hadiah Uang Tunai & Internet Gratis!* 🎊\n";
                    $message .= "Ajak teman atau keluarga untuk bergabung bersama kami, dan kamu berkesempatan mendapatkan:\n";
                    $message .= "💰 *Hadiah Uang Tunai!*\n";
                    $message .= "📶 *Potongan 100% Internet Gratis!*\n";
                    $message .= "Hadiah akan diundi setiap akhir bulan, semakin banyak kamu mereferensikan, semakin besar peluang menang! 🔥\n\n";
                    $message .= "Segera lunasi tagihan dan tukarkan kuponmu dengan hadiah spesial! 🎊🔥\n\n";
                    $message .= "📍 *Datang dan bayar di kantor sekarang!* 🚀\n";
                    $message .= "📍 *Lokasi:* (https://maps.app.goo.gl/7GBV2Kgy1SM2rZGM8)\n\n";
                    $message .= "📞 *Kontak Kami:*\n";
                    $message .= "📌 *Admin*   : 0857-9392-0206 (*Agisna* 🧕🏻)\n";
                    $message .= "📌 *CS*      : 0857-2222-0169 (*Gilang* 👳🏻‍♂️)\n";
                    $message .= "📌 *Info Pemasangan* : 0821-2385-2983 (*Adit* 👳🏻‍♂️)\n\n";
                    $message .= "🙏🏻 Semoga ibadah dan usaha kita diterima di bulan suci ini. Terima kasih atas kepercayaan Anda! 🌟\n\n";
                    $message .= "🔹 *Powered by netdigitalgroup.com* 🔹\n\n";


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



    public function store_bayar25(Request $request)
    {
        $request->validate([
            'target' => 'required|array',
            'token_id' => 'required|exists:bot_tokens,id',
        ]);

        $tokenData = DB::table('bot_tokens')->find($request->token_id);
        $token = $tokenData->token;

        // Daftar paket
        $paketList = [
            1 => '5 Mbps',
            2 => '10 Mbps',
            3 => '15 Mbps',
            4 => '25 Mbps',
        ];

        $targetNumbers = $request->input('target');

        try {
            foreach ($targetNumbers as $target) {
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if ($pelanggan) {
                    // Konversi tanggal tagihan
                    $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                    $formattedDate = $tglTagihPlg->format('d F Y');

                    // Ambil jenis paket dari daftar
                    $paket = $paketList[$pelanggan->paket_plg] ?? 'Unknown';


                    // Tambahkan promo Ramadhan
                    $message = "🔥💸 *DISKON SPESIAL RAMADHAN! BAYAR LEBIH AWAL, UNTUNG LEBIH BANYAK!* 🌙✨\n\n";
                    $message .= "🌙🌟 *Marhaban ya Ramadhan* 🌟🌙\n";
                    $message .= "Pelanggan Terhormat,\nBapak/Ibu *{$pelanggan->nama_plg}*,\n\n";
                    $message .= "Di bulan penuh berkah ini, Net Digital Group menghadirkan *promo spesial* untuk Anda! Jangan lewatkan kesempatan mendapatkan *kupon hadiah langsung* dengan membayar tagihan internet *sebelum tanggal 25* di kantor kami! 🎉\n\n";
                    $message .= "💡 *Syarat mudah:*\n";
                    $message .= "✅ Bayar langsung ke kantor sebelum tanggal 25\n";
                    $message .= "✅ Dapatkan *kupon hadiah* yang bisa ditukar dengan hadiah menarik 🎁\n";
                    $message .= "✅ Dapatkan *Potongan pembayaran internet* dengan mengajak teman atau saudara untuk berlangganan WiFi! 🎁\n";
                    $message .= "Segera lunasi tagihan dan tukarkan kuponmu dengan hadiah spesial! 🎊🔥\n";
                    $message .= "📍 *Datang dan bayar di kantor sekarang!* 🚀\n";
                    $message .= "📍 *Lokasi:* (https://maps.app.goo.gl/7GBV2Kgy1SM2rZGM8)\n\n";

                    // Format pesan

                    $message .= "*Pelanggan YTH:*\n";
                    $message .= "Nama : *{$pelanggan->nama_plg}*\n";
                    $message .= "Tagihan Bulan : " . now()->format('F Y') . "\n";
                    $message .= "Jenis Paket : {$paket}\n";
                    $message .= "Biaya Paket : Rp. {$pelanggan->harga_paket}\n";
                    $message .= "*Total Besar Tagihan + PPN : Rp. {$pelanggan->harga_paket}*\n";
                    $message .= "Masa aktif s/d {$formattedDate}\n";
                    $message .= "Ket : *BELUM TERBAYAR*\n\n";
                    $message .= "PEMBAYARAN:\n";
                    $message .= "- via transfer : rek BCA : 3770198576 atas nama *Ruslandi* \n";
                    $message .= "- *Pembayaran Via Penjemputan/Pickup dikenakan biaya jasa pengambilan Rp.5000*\n";
                    $message .= "Dimohon untuk Melampirkan bukti pembayaran apabila sudah melakukan pembayaran.\n\n";



                    $message .= "📞 *Kontak Kami:*\n";
                    $message .= "📌 *Admin*   : 0857-9392-0206 (*Agisna* 🧕🏻)\n";
                    $message .= "📌 *CS*      : 0857-2222-0169 (*Gilang* 👳🏻‍♂️)\n";
                    $message .= "📌 *Info Pemasangan* : 0821-2385-2983 (*Adit* 👳🏻‍♂️)\n\n";
                    $message .= "🙏🏻 Semoga ibadah dan usaha kita diterima di bulan suci ini. Terima kasih atas kepercayaan Anda! 🌟\n\n";
                    $message .= "🔹 *Powered by netdigitalgroup.com* 🔹\n\n";

                    // Kirim pesan via API Fonnte
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




    public function plg_of(Request $request)
    {
        // $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'Block', 'Isolir']);
        $query = Pelangganof::whereNotIn('status_pembayaran', ['n']);

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

        return view('whatsapp.pelanggan_of', compact('pelanggan', 'botTokens'));
    }



    public function store_plg_of(Request $request)
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
                $pelanggan = Pelangganof::where('no_telepon_plg', $target)->first();

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

                    $message = "🌙 *Promo Spesial Ramadhan untuk Pelanggan Setia!* 🌙\n\n";
                    $message .= "✨ *Kabar Gembira!* ✨\n";
                    $message .= "Halo Pelanggan yang terhormat ,\n\n";
                    $message .= "Spesial buat kamu yang belum membayar lebih dari *3 bulan*, kini saatnya kembali menikmati internet cepat dengan *promo spesial di bulan suci Ramadhan*! 🎁🎉\n\n";
                    $message .= "📢 *GRATIS BIAYA INSTALASI!* \n";
                    $message .= "Kamu hanya perlu membayar sesuai dengan paket pilihanmu, *tanpa biaya tambahan*! 🔥\n\n";
                    $message .= "💡 *Manfaatkan kesempatan ini sekarang juga!* \n";
                    $message .= "✅ *Tanpa Biaya Instalasi*\n";
                    $message .= "✅ *Cukup Bayar Paket Saja*\n";
                    $message .= "✅ *Tidak Ada Denda*\n";
                    $message .= "✅ *Koneksi Stabil untuk Ramadan yang Lebih Berkah*\n\n";
                    $message .= "🚀 *Jangan sampai ketinggalan!* Promo ini hanya berlaku selama bulan Ramadhan! \n\n";
                    $message .= "🎉 *Bonus Spesial! Dapatkan Hadiah Uang Tunai & Internet Gratis!* 🎉\n";
                    $message .= "Ajak teman atau keluarga untuk bergabung bersama kami, dan kamu berkesempatan mendapatkan:\n";
                    $message .= "💰 *Hadiah Uang Tunai!*\n";
                    $message .= "📶 *Potongan 100% Internet Gratis!*\n";
                    $message .= "Hadiah akan diundi setiap akhir bulan, semakin banyak kamu mereferensikan, semakin besar peluang menang! 🔥\n\n";
                    $message .= "📞 *Hubungi kami sekarang untuk aktivasi ulang:*\n";
                    $message .= "📌 *Admin* : 0857-9392-0206 (*Agisna* 🧕🏻)\n";
                    $message .= "📌 *CS* : 0857-2222-0169 (*Gilang* 👳🏻‍♂️)\n";
                    $message .= "📌 *Info Pemasangan* : 0821-2385-2983 (*Adit* 👳🏻‍♂️)\n";
                    $message .= "🌟 *Sambut Ramadhan dengan koneksi cepat & berkah tanpa hambatan!* 🌟 \n\n";
                    $message .= "🔹 *Powered by netdigitalgroup.com* \n";



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




    public function promo_tgl25(Request $request)
    {
        $query = Pelanggan::whereNotIn('status_pembayaran', ['paid', 'PSB', 'Reactivasi']);
        //  $query = Pelanggan::whereNotIn('paket_plg', 'vcr');

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

        return view('whatsapp.promo_tgl25', compact('pelanggan', 'botTokens'));
    }

    /////


    public function store_promo_tgl25(Request $request)
    {
        $request->validate([
            'target' => 'required|array',
            'token_id' => 'required|exists:bot_tokens,id',
        ]);

        $tokenData = DB::table('bot_tokens')->find($request->token_id);
        $token = $tokenData->token;

        // Daftar paket
        $paketList = [
            1 => '5 Mbps',
            2 => '10 Mbps',
            3 => '15 Mbps',
            4 => '25 Mbps',
        ];

        $targetNumbers = $request->input('target');

        try {
            foreach ($targetNumbers as $target) {
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if ($pelanggan) {
                    // Konversi tanggal tagihan
                    $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                    $formattedDate = $tglTagihPlg->format('d F Y');

                    // Ambil jenis paket dari daftar
                    $paket = $paketList[$pelanggan->paket_plg] ?? 'Unknown';

                    // Tambahkan promo Ramadhan

                    $message = "🤖 *Bot Otomatis*\n\n";
                    $message .= "🌙 *Marhaban ya Ramadhan* 🌙\n";
                    $message .= "Pelanggan Terhormat,\nBapak/Ibu *{$pelanggan->nama_plg}*,\n\n";
                    $message .= "🚨 *Hari ini adalah kesempatan terakhir Anda* untuk mendapatkan *kupon hadiah langsung* dengan membayar tagihan secara langsung, datang ke kantor kami! Jangan sampai ketinggalan! 🎉\n\n";
                    $message .= "💡 *Syarat mudah:*\n";
                    $message .= "✅ Lunasi Tagihan anda sebelum tanggal 28 *(Berlaku untuk jatuh tempo pembayaran 27 s/d 30)*\n";
                    $message .= "✅ Dapatkan *kupon hadiah* yang bisa ditukar dengan hadiah menarik 🎁\n";
                    $message .= "✅ Bonus *potongan pembayaran* dengan mengajak teman/saudara berlangganan WiFi! 🔥\n\n";
                    $message .= "Jangan sampai melewatkan promo spesial ini! 🚀🎊\n";
                    $message .= "📍 *Datang dan bayar di kantor sekarang!* 🚀\n";
                    $message .= "📍 *Lokasi kantor:* (https://maps.app.goo.gl/7GBV2Kgy1SM2rZGM8)\n\n";

                    $message .= "📞 *Kontak Kami:*\n";
                    $message .= "📌 *CS*   : 0857-9392-0206 (*Agisna* 🧕🏻)\n";
                    $message .= "📌 *Admin*      : 0857-2222-0169 (*Gilang* 👳🏻‍♂️)\n";
                    //   $message .= "📌 *Info Pemasangan* : 0821-2385-2983 (*Adit* 👳🏻‍♂️)\n\n";
                    $message .= "🙏🏻 Semoga ibadah dan usaha kita diterima di bulan suci ini. Terima kasih atas kepercayaan Anda! 🌟\n\n";
                    $message .= "🔹 *Powered by netdigitalgroup.com* 🔹\n\n";

                    // Kirim pesan via API Fonnte
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



    public function ucapan_id2025(Request $request)
    {
        $query = Pelanggan::query(); // Menggunakan query builder agar bisa difilter

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

        // Ambil data pelanggan setelah filter
        $pelanggan = $query->get(['id_plg', 'nama_plg', 'no_telepon_plg', 'tgl_tagih_plg']);

        // Ambil data token dari tabel bot_tokens
        $botTokens = DB::table('bot_tokens')->get(['id', 'name', 'token']);

        return view('whatsapp.ucapan_id2025', compact('pelanggan', 'botTokens'));
    }




    public function store_ucapan_id2025(Request $request)
    {
        $request->validate([
            'target' => 'required|array',
            'token_id' => 'required|exists:bot_tokens,id',
        ]);

        $tokenData = DB::table('bot_tokens')->find($request->token_id);
        $token = $tokenData->token;

        // Daftar paket
        $paketList = [
            1 => '5 Mbps',
            2 => '10 Mbps',
            3 => '15 Mbps',
            4 => '25 Mbps',
        ];

        $targetNumbers = $request->input('target');

        try {
            foreach ($targetNumbers as $target) {
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if ($pelanggan) {
                    // Konversi tanggal tagihan
                    $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                    $formattedDate = $tglTagihPlg->format('d F Y');

                    // Ambil jenis paket dari daftar
                    $paket = $paketList[$pelanggan->paket_plg] ?? 'Unknown';

                    // Tambahkan nama pelanggan dalam pesan
                    $message = "🤖 *Bot Otomatis*\n\n";
                    $message .= "📢 *Selamat Hari Raya Idul Fitri 1446 H* 🌙✨\n\n";
                    $message .= "Pelanggan Setia,\nBapak/Ibu *{$pelanggan->nama_plg}*,\n\n";
                    $message .= "السلام عليكم ورحمة اللّٰه وبركاته\n\n";
                    $message .= "Menjelang berakhirnya Bulan Suci Ramadhan, dan dengan segala kerendahan hati, kami memohon maaf atas segala salah dan khilaf.\n\n";
                    $message .= "*SELAMAT HARI RAYA IDUL FITRI 1 Syawal 1446 Hijriah*\n\n";
                    $message .= "‎تَقَبَّلَ اللَّهُ مِنَّا وَمِنْكُمْ صِيَامَنَا وَصِيَامَكُمْ تَقَبَّلْ يَا كَرِيْمَ\n\n";
                    $message .= "_Taqabbalallohu Minna wa Minkum shiyaamanaa washiyaamakum taqabbal yaa Kariim_\n\n";
                    $message .= "Kami segenap tim *Net Digital Group* mengucapkan selamat hari raya Idul Fitri, mohon maaf lahir dan batin.\n\n";
                    $message .= "Semoga Allah SWT mengampuni dosa kita, menerima amal ibadah dan puasa kita, serta dipertemukan kembali dengan Ramadhan yang akan datang dalam keadaan sehat.\n\n";
                    $message .= "*Aamiin Yaa Robbal 'Alamiin* 🤲\n\n";
                    $message .= "‎وَالسَّلاَمُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَاتُهُ\n\n";
                    $message .= "📞 *Kontak Kami:*\n";
                    $message .= "📌 *CS*   : 0857-9392-0206 (*Agisna* 🧕🏻)\n";
                    $message .= "📌 *Admin* : 0857-2222-0169 (*Gilang* 👳🏻‍♂️)\n\n";
                    //   $message .= "📌 *Info Pemasangan* : 0821-2385-2983 (*Adit* 👳🏻‍♂️)\n\n";
                    $message .= "🔹 *Powered by netdigitalgroup.com* 🔹\n\n";

                    // Kirim pesan via API Fonnte
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

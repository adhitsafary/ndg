<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    private $telegramToken = '7558654529:AAE4GLCbqr5bnFj_P04Ll8KMFUmJ6sxg7aM'; // Token bot Telegram

    public function webhook(Request $request)
    {
        $update = $request->all();
        $chatId = $update['message']['chat']['id'];
        $text = $update['message']['text'];

        // Cek jika perintah adalah pencarian pelanggan
        if (strpos($text, '/cari_pelanggan') === 0) {
            $id_plg = trim(str_replace('/cari_pelanggan', '', $text));

            // Misalkan Anda menggunakan model Pelanggan untuk mencari data
            $pelanggan = Pelanggan::where('id_plg', $id_plg)->first();

            if ($pelanggan) {
                $message = "Pelanggan ditemukan:\n";
                $message .= "Nama: " . $pelanggan->nama_plg . "\n";
                $message .= "Alamat: " . $pelanggan->alamat_plg . "\n";
                $message .= "Telepon: " . $pelanggan->no_telepon_plg . "\n";
                // Tambahkan informasi lain sesuai kebutuhan
            } else {
                $message = "Pelanggan dengan ID $id_plg tidak ditemukan.";
            }
        } else {
            $message = "Perintah tidak dikenali.";
        }

        // Kirim pesan ke Telegram
        $this->sendMessage($chatId, $message);

        return response()->json(['status' => 'success']);
    }

    // Fungsi untuk mengirim pesan ke Telegram
    private function sendMessage($chatId, $message)
    {
        $url = "https://api.telegram.org/bot{$this->telegramToken}/sendMessage?chat_id={$chatId}&text=" . urlencode($message);
        file_get_contents($url);  // Kirim permintaan ke Telegram API untuk mengirim pesan
    }
}

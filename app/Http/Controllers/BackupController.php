<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        return view('backup.index');
    }

    public function backup()
    {
        // Ambil data koneksi dari .env
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE', 'netdigit_netnet');
        $dbUser = env('DB_USERNAME', 'netdigit_netdigitalgroup');
        $dbPass = env('DB_PASSWORD', 'netdigitalgroup');

        // Nama file backup
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $filepath = storage_path('app/' . $filename);

        // Jalankan mysqldump untuk backup database
        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} --port={$dbPort} {$dbName} > {$filepath}";
        system($command);

        // Cek apakah file berhasil dibuat
        if (!file_exists($filepath)) {
            $this->sendTelegramMessage("❌ Backup Gagal! Database {$dbName} tidak berhasil dibackup.");
            return redirect()->back()->with('error', 'Backup gagal dibuat.');
        }

        // Kirim file backup ke Telegram
        $this->sendTelegramFile($filepath, "✅ Backup Berhasil! Database {$dbName} telah dibackup pada " . Carbon::now()->format('d-m-Y H:i:s'));

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Fungsi untuk mengirim pesan teks ke Telegram
     */
    private function sendTelegramMessage($message)
    {
        $botToken = "7928689428:AAEST1keOevviTKTAZdOL2Y-MTbKuaYuXZY";
        $chatId = "-4730728358"; // ID Grup atau User

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        Http::post($url, [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }

    /**
     * Fungsi untuk mengirim file ke Telegram
     */
    private function sendTelegramFile($filePath, $caption = '')
    {
        $botToken = "7928689428:AAEST1keOevviTKTAZdOL2Y-MTbKuaYuXZY";
        $chatId = "-4730728358"; // ID Grup atau User

        $url = "https://api.telegram.org/bot{$botToken}/sendDocument";

        Http::attach('document', file_get_contents($filePath), basename($filePath))
            ->post($url, [
                'chat_id' => $chatId,
                'caption' => $caption
            ]);
    }
}

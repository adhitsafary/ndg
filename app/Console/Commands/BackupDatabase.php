<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BackupDatabase extends Command
{
    protected $signature = 'backup:db';
    protected $description = 'Backup database dan kirim ke Telegram';

    public function handle()
    {
        // Ambil data koneksi dari .env
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE', 'netdigit_netnet');
        $dbUser = env('DB_USERNAME', 'netdigit_netdigitalgroup');
        $dbPass = env('DB_PASSWORD', 'netdigitalgroup');

        // Daftar hari dalam bahasa Indonesia
        $hariIndonesia = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        // Ambil nama hari saat ini dalam bahasa Indonesia
        $hari = $hariIndonesia[Carbon::now()->format('l')];

        // Nama file backup
        $filename = "backup-{$hari}_" . Carbon::now()->format('Y-m-d_H-i-s') . ".sql";
        $filepath = storage_path("app/{$filename}");

        // Jalankan mysqldump untuk backup database
        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} --port={$dbPort} {$dbName} > {$filepath}";
        system($command);

        // Cek apakah file berhasil dibuat
        if (!file_exists($filepath)) {
            $this->sendTelegramMessage("❌ Backup Gagal! Database {$dbName} tidak berhasil dibackup.");
            $this->error('Backup gagal dibuat.');
            return;
        }

        // Kirim file backup ke Telegram
        $this->sendTelegramFile($filepath, "✅ Backup Berhasil! Hari ini *{$hari}*, " . Carbon::now()->format('d-m-Y H:i:s'));

        $this->info('Backup selesai & dikirim ke Telegram!');
    }

    /**
     * Fungsi untuk mengirim pesan teks ke Telegram
     */
    private function sendTelegramMessage($message)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN', '7928689428:AAEST1keOevviTKTAZdOL2Y-MTbKuaYuXZY');
        $chatId = env('TELEGRAM_CHAT_ID', '-4730728358');

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        Http::post($url, [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    }

    /**
     * Fungsi untuk mengirim file ke Telegram
     */
    private function sendTelegramFile($filePath, $caption = '')
    {
        $botToken = env('TELEGRAM_BOT_TOKEN', '7928689428:AAEST1keOevviTKTAZdOL2Y-MTbKuaYuXZY');
        $chatId = env('TELEGRAM_CHAT_ID', '-4730728358');

        $url = "https://api.telegram.org/bot{$botToken}/sendDocument";

        Http::attach('document', file_get_contents($filePath), basename($filePath))
            ->post($url, [
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'Markdown'
            ]);
    }
}

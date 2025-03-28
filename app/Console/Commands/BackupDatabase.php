<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'backup:db';
    protected $description = 'Backup database dan kirim ke Telegram';

    public function handle()
    {
        // Nama file backup dengan format lebih rapi
        $filename = 'backup-' . Carbon::now()->locale('id')->translatedFormat('l-d-m-Y_H-i-s') . '.sql';
        $filepath = storage_path('app/' . $filename);

        // Jalankan mysqldump untuk backup database
        $command = "mysqldump --user=netdigit_netdigitalgroup --password=netdigitalgroup --host=127.0.0.1 netdigit_netdigitalgroup > {$filepath}";
        exec($command, $output, $result);

        // Cek apakah file berhasil dibuat dan tidak kosong
        if ($result !== 0 || !file_exists($filepath) || filesize($filepath) === 0) {
            $this->sendTelegramMessage("❌ Backup Gagal! Database tidak berhasil dibackup.");
            return;
        }

        // Kirim file backup ke Telegram
        $this->sendTelegramFile($filepath, "✅ Backup Berhasil! Database telah dibackup pada " . Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i:s'));

        $this->info('Backup selesai & dikirim ke Telegram!');
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

        Http::attach('document', fopen($filePath, 'r'), basename($filePath))
            ->post($url, [
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'HTML'
            ]);
    }
}

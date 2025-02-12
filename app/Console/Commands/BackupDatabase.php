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
        // Nama file backup
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $filepath = storage_path('app/' . $filename);

        // Jalankan mysqldump untuk backup database
        $command = "mysqldump --user=netdigit_netdigitalgroup --password=netdigitalgroup --host=127.0.0.1 netdigit_netnet";
        system($command . " > $filepath");


        system($command);

        // Kirim ke Telegram
        $token = "7928689428:AAEST1keOevviTKTAZdOL2Y-MTbKuaYuXZY";  // Ganti dengan token bot kamu
        $chat_id = "-4730728358";  // Ganti dengan chat ID atau grup ID

        $response = Http::attach('document', file_get_contents($filepath), $filename)
            ->post("https://api.telegram.org/bot{$token}/sendDocument", [
                'chat_id' => $chat_id,
                'caption' => "Backup Database: " . Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        $this->info('Backup selesai & d

         ikirim ke Telegram!');
    }
}

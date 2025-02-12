<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AmbilDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ambil-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Memanggil fungsi untuk ambil data dan simpan ke database
        $this->ambilData();
    }

}

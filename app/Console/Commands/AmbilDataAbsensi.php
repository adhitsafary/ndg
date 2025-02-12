<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\X100Controller;

class AmbilDataAbsensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:ambil';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengambil data absensi dari mesin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $controller = new X100Controller();
        $controller->ambilData();
        $this->info('Data absensi berhasil diambil.');
    }
}

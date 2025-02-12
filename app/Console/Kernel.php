<?php

namespace App\Console;

use App\Http\Controllers\IsolirController;
use App\Http\Controllers\PelangganController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Jadwal untuk pengecekan status pembayaran setiap hari
        $schedule->command('pembayaran:cek')->daily();

        // Penjadwalan untuk pengecekan status isolir setiap hari
        $schedule->call(function () {
            app(\App\Http\Controllers\IsolirController::class)->checkIsolirStatus();
        })->daily();

        // Penjadwalan untuk membersihkan pelanggan isolir lebih dari 60 hari
        $schedule->command('isolir:cleanup')->daily();

        // Jalankan fungsi checkAndMoveToIsolir setiap hari
        $schedule->call(function () {
            (new PelangganController)->checkAndMoveToIsolir();
        })->daily();

        // Jalankan fungsi updatePaymentStatus setiap hari
        $schedule->call(function () {
            (new PelangganController)->updatePaymentStatus();
        })->daily();

        $schedule->command('ambil:data')->everyMinute(); // Menjadwalkannya setiap menit


        $schedule->command('absensi:ambil')->everyTwoMinutes();  // Menjalankan setiap 5 menit

        $schedule->command('backup:db')->dailyAt('00:00');

        $schedule->command('updatePaymentStatus')->dailyAt('01:00');

    }


    protected $commands = [
        Commands\CleanUpIsolir::class,
    ];


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected $routeMiddleware = [
        // Middleware lainnya
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}

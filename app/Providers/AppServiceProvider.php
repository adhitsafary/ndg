<?php

namespace App\Providers;

use App\Models\Pemberitahuan;
use App\Models\Pesan;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        // Set global locale to Indonesian
        Carbon::setLocale('id');

        // Mengatur layout berdasarkan role pengguna
        View::composer('*', function ($view) {
            $role = auth()->user()->role ?? null;

            // Tentukan layout berdasarkan role
            if ($role === 'superadmin') {
                $layout = 'superadmin.layout_superadmin';
            } elseif ($role === 'admin') {
                $layout = 'layout'; // Layout untuk admin
            } elseif ($role === 'finance') {
                $layout = 'layout_finance'; // Layout untuk finanace
            } elseif ($role === 'teknisi') {
                $layout = 'layout_teknisi'; // Layout untuk teknisi
            } else {
                $layout = 'login.login'; // Layout default jika role tidak dikenali
            }

            // Berikan layout ke view
            $view->with('layout', $layout);
        });


        // Ambil semua pemberitahuan
        $pemberitahuan = Pemberitahuan::all();

        // Bagikan ke semua view
        View::share('pemberitahuan', $pemberitahuan);


        $pesan = Pesan::all();
        // Bagikan ke semua view
        View::share('pesan', $pesan);
    }
}

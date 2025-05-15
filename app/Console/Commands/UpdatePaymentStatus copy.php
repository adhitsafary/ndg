<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelanggan;
use App\Models\BayarPelanggan;
use Carbon\Carbon;

class UpdatePaymentStatus extends Command
{
    protected $signature = 'updatePaymentStatus';
    protected $description = 'Update status pembayaran setiap hari';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Ambil semua pelanggan dengan status yang perlu diperbarui
        $pelanggans = Pelanggan::whereNotIn('status_pembayaran', ['Isolir'])->get();

        foreach ($pelanggans as $pelanggan) {
            // Jika status sudah "Isolir", lewati pelanggan ini tanpa mengubah status
            if ($pelanggan->status_pembayaran === 'Isolir') {
                continue;
            }

            // Tambahkan logika untuk pelanggan dengan status "PSB" atau "Reaktivasi"
            if (in_array($pelanggan->status_pembayaran, ['PSB', 'Reactivasi'])) {
                // Jika hari ini belum tanggal 1, abaikan perubahan status
                if (Carbon::now()->day !== 1) {
                    continue;
                } else {
                    // Jika sudah tanggal 1, ubah status menjadi "unpaid"
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            }

            // Ambil pembayaran terakhir dari tabel BayarPelanggan berdasarkan id_plg
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('tanggal_pembayaran', 'desc')
                ->first();

            // Ambil tanggal pembayaran terakhir jika ada
            $createdAtPembayaran = $pembayaranTerakhir ? Carbon::parse($pembayaranTerakhir->tanggal_pembayaran) : null;

            // Ambil tanggal tagihan terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray); // Ambil tanggal terakhir dari array

            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                // Buat tanggal tagihan lengkap dengan format Y-m-d
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // 🔥 **Perbaikan utama**: Pastikan pelanggan yang sudah membayar lebih dari bulan ini tetap "paid"
                if ($createdAtPembayaran && ($createdAtPembayaran->year > $currentYear ||
                    ($createdAtPembayaran->year == $currentYear && $createdAtPembayaran->month > $currentMonth))) {
                    // Jika pelanggan sudah membayar untuk bulan mendatang, status tetap "paid"
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                    continue; // Stop pemrosesan lebih lanjut untuk pelanggan ini
                }

                // Jika ada pembayaran bulan ini, status tetap "paid"
                if (
                    $createdAtPembayaran && $createdAtPembayaran->month === Carbon::now()->month &&
                    $createdAtPembayaran->year === Carbon::now()->year
                ) {
                    $pelanggan->status_pembayaran = 'paid';
                    $pelanggan->save();
                    continue; // Stop pemrosesan lebih lanjut untuk pelanggan ini
                }

                // Jika sudah melewati tanggal tagihan dan belum membayar, status menjadi "Isolir"
                if (Carbon::now()->greaterThan($tglTagihPlg) && !Carbon::now()->isSameDay($tglTagihPlg)) {
                    $pelanggan->status_pembayaran = 'Isolir';
                } else {
                    // Jika belum melewati tanggal tagihan atau tepat di tanggal tagihan, status tetap "unpaid"
                    $pelanggan->status_pembayaran = 'unpaid';
                }
            } else {
                // Jika tanggal tagihan tidak valid, set status default "unpaid"
                $pelanggan->status_pembayaran = 'unpaid';
            }

            // Simpan perubahan status pelanggan
            $pelanggan->save();
        }

        $this->info('Status pembayaran telah diperbarui.');
    }
}

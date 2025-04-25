<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bukti_byr_plg', function (Blueprint $table) {
            $table->id();
            $table->string('id_plg');
            $table->date('tanggal_pembayaran');
            $table->integer('jumlah_pembayaran');
            $table->string('metode_transaksi');
            $table->string('nama_pengirim')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();

            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_byr_plg');
    }
};

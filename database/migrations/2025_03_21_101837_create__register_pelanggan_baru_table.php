<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('register_pelanggan_baru', function (Blueprint $table) {
            $table ->string('nama_plg');
            $table->string('nik_plg');
            $table->string('no_tlp_plg');
            $table->string('email_plg');
            $table->string('alamat_plg');
            $table->string('desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->string('paket_plg');
            $table->id();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_register_pelanggan_baru');
    }
};

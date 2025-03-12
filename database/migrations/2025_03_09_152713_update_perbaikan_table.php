<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->json('teknisi')->nullable()->after('info'); // Simpan teknisi sebagai array JSON
            $table->json('inventory_keluar')->nullable()->after('teknisi'); // Simpan barang keluar sebagai JSON
            $table->decimal('total_biaya', 10, 2)->default(0)->after('inventory_keluar'); // Total harga barang
            $table->string('nomor_tiket')->nullable()->after('total_biaya'); // Nomor tiket perbaikan
            $table->string('kd_tiket')->nullable()->after('nomor_tiket'); // Kode tiket unik
        });
    }

    public function down()
    {
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->dropColumn(['teknisi', 'inventory_keluar', 'total_biaya', 'nomor_tiket', 'kd_tiket']);
        });
    }
};

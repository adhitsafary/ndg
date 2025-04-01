<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inventory_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->string('id_plg'); // Ganti dari kd_tiket ke id_plg
            $table->string('nm_brg');
            $table->integer('jumlah_keluar');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->string('admin');
            $table->date('tanggal_keluar');

            // Foreign key berdasarkan id_plg
            $table->foreign('id_plg')->references('id_plg')->on('perbaikan')->onDelete('cascade');

            // Foreign key ke tabel inventory
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_keluar');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inventory_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perbaikan_id'); // ID dari tabel perbaikan
            $table->string('nm_brg'); // Nama barang
            $table->integer('jml_brg'); // Jumlah barang yang keluar
            $table->decimal('harga_satuan', 10, 2)->nullable(); // Harga satuan barang
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('perbaikan_id')->references('id')->on('perbaikan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_keluar');
    }
};

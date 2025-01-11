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
        Schema::create('adaptor', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('kode_barang');
            $table->string('sisa_barang');
            $table->string('pic');
            $table->string('petugas');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adaptor');
    }
};

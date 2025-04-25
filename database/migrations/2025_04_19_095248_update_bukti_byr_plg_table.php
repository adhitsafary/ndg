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
        Schema::table('bukti_byr_plg', function (Blueprint $table) {
            $table->string('nama_plg');
            $table->string('alamat_plg');
            $table->string('no_telepon_plg');
            $table->string('harga_paket');
            $table->string('tgl_tagih_plg');


        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

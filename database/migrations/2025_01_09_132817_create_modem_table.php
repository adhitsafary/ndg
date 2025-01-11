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
        Schema::create('modem', function (Blueprint $table) {
            $table->id();
            $table->string('sn_modem');
            $table->string('model');
            $table->string('tgl_keluar');
            $table->string('user');
            $table->string('keterangan');
            $table->string('id_mikrotik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modem');
    }
};

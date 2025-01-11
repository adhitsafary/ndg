<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('generator_id', function (Blueprint $table) {
            $table->string('kode_perusahaan')->nullable();
            $table->string('kode_paket_plg')->nullable();
            $table->string('kode_nik')->nullable();
            $table->string('kode_odp')->nullable();
        });
    }

    public function down()
    {
        Schema::table('generator_id', function (Blueprint $table) {
            $table->dropColumn('kode_perusahaan');
            $table->dropColumn('kode_paket_plg');
            $table->dropColumn('kode_nik');
            $table->dropColumn('kode_odp');
        });
    }
};

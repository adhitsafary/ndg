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
        Schema::table('inventory_keluar', function (Blueprint $table) {
            $table->unsignedBigInteger('rekap_pemasangan_id')->nullable()->after('id');
            $table->foreign('rekap_pemasangan_id')->references('id')->on('rekap_pemasangan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_keluar', function (Blueprint $table) {
            //
        });
    }
};

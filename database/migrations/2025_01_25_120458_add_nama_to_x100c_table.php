<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('x100c', function (Blueprint $table) {
            $table->string('nama')->nullable()->after('pin'); // Tambahkan kolom nama
        });
    }

    public function down(): void
    {
        Schema::table('x100c', function (Blueprint $table) {
            $table->dropColumn('nama');
        });
    }
};

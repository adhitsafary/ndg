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
        Schema::create('data_odp', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tipe');
            $table->string('maps');
            $table->string('foto')->nullable(); // Untuk menyimpan path foto
            $table->decimal('latitude', 10, 8)->nullable(); // Untuk latitude
            $table->decimal('longitude', 11, 8)->nullable(); // Untuk longitude
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_odp');
    }
};

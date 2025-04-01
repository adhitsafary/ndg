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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string("nm_brg");
            $table->integer("jml_brg");
            $table->enum("satuan", ["meter", "pcs"]);
            $table->decimal("harga_satuan", 10, 2); // Harga per satuan
            $table->decimal("harga_total", 10, 2)->storedAs('jml_brg * harga_satuan'); // Harga total otomatis dihitung
            $table->string("kategori");
            $table->string("admin");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};

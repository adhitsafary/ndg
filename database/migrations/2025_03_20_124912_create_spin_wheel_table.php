<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('spin_wheel', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pilihan
            $table->integer('chance'); // Peluang menang (bobot)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spin_wheel');
    }
};

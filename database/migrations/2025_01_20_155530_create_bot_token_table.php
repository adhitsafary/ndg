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
        Schema::create('bot_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_tokens');
    }
};

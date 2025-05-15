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
        Schema::create('olt_scripts', function (Blueprint $table) {
            $table->id();
            $table->string('sn');
            $table->string('port');
            $table->string('slot');
            $table->string('pon');
            $table->integer('number_onu');
            $table->string('type_modem');
            $table->string('nama_modem');
            $table->string('tcont_profile');
            $table->integer('vlan');
            $table->string('mode');
            $table->string('pppoe_user')->nullable();
            $table->string('pppoe_pass')->nullable();
            $table->text('generated_script');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olt_scripts');
    }
};

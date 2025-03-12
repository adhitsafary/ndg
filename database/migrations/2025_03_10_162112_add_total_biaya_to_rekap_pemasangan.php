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
        Schema::table('rekap_pemasangan', function (Blueprint $table) {
            $table->decimal('total_biaya', 10, 2)->default(0.00)->after('maps');
        });
    }

    public function down()
    {
        Schema::table('rekap_pemasangan', function (Blueprint $table) {
            $table->dropColumn('total_biaya');
        });
    }


};

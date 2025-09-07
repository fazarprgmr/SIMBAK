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
        Schema::table('rkas', function (Blueprint $table) {
            $table->integer('bulan')->nullable()->after('id');
            $table->integer('tahun')->nullable()->after('bulan');
        });
    }

    public function down()
    {
        Schema::table('rkas', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });
    }
};

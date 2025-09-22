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
    Schema::table('rkas', function (Blueprint $table) {
        $table->unsignedBigInteger('kode_rekening_id')->nullable()->after('id');
        $table->foreign('kode_rekening_id')->references('id')->on('kode_rekenings')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('rkas', function (Blueprint $table) {
        $table->dropForeign(['kode_rekening_id']);
        $table->dropColumn('kode_rekening_id');
    });
}

};

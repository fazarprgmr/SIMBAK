<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rkas', function (Blueprint $table) {
            $table->string('uraian', 255)->nullable()->change();
            $table->string('kode_rekening', 50)->nullable()->change(); // sekalian ini juga
        });
    }

    public function down(): void
    {
        Schema::table('rkas', function (Blueprint $table) {
            $table->string('uraian', 255)->nullable(false)->change();
            $table->string('kode_rekening', 50)->nullable(false)->change();
        });
    }
};

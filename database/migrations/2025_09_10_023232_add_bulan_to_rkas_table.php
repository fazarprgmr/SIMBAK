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
            $table->string('bulan', 2)->after('id'); // simpan '01' sampai '12'
        });
    }

    public function down(): void
    {
        Schema::table('rkas', function (Blueprint $table) {
            $table->dropColumn('bulan');
        });
    }
};

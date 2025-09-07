<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('rkas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rekening', 50);
            $table->string('uraian');
            $table->string('sub_uraian')->nullable();


            // === Saldo Awal ===
            $table->enum('saldo_awal_mode', ['total', 'detail'])->default('total');
            $table->decimal('saldo_awal_jumlah', 20, 2)->nullable();
            $table->string('saldo_awal_satuan', 50)->nullable();
            $table->decimal('saldo_awal_harga', 20, 2)->nullable();
            $table->decimal('saldo_awal_total', 20, 2)->nullable();


            // === Pembelian ===
            $table->enum('pembelian_mode', ['total', 'detail'])->default('total');
            $table->decimal('pembelian_jumlah', 20, 2)->nullable();
            $table->string('pembelian_satuan', 50)->nullable();
            $table->decimal('pembelian_harga', 20, 2)->nullable();
            $table->decimal('pembelian_total', 20, 2)->nullable();


            // === Saldo Akhir ===
            $table->enum('saldo_akhir_mode', ['total', 'detail'])->default('total');
            $table->decimal('saldo_akhir_jumlah', 20, 2)->nullable();
            $table->string('saldo_akhir_satuan', 50)->nullable();
            $table->decimal('saldo_akhir_harga', 20, 2)->nullable();
            $table->decimal('saldo_akhir_total', 20, 2)->nullable();


            // === Persediaan Rusak (opsional) ===
            $table->enum('rusak_mode', ['total', 'detail'])->default('total');
            $table->decimal('rusak_jumlah', 20, 2)->nullable();
            $table->string('rusak_satuan', 50)->nullable();
            $table->decimal('rusak_harga', 20, 2)->nullable();
            $table->decimal('rusak_total', 20, 2)->nullable();


            // === Beban Persediaan ===
            $table->enum('beban_mode', ['total', 'detail'])->default('total');
            $table->decimal('beban_jumlah', 20, 2)->nullable();
            $table->string('beban_satuan', 50)->nullable();
            $table->decimal('beban_harga', 20, 2)->nullable();
            $table->decimal('beban_total', 20, 2)->nullable();


            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('rkas');
    }
};

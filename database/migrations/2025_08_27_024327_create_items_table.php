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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('items')->onDelete('cascade');
            $table->string('kode_rekening')->nullable();
            $table->string('uraian');
            $table->string('sub_uraian')->nullable();

            // Untuk detail (child)
            $table->integer('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

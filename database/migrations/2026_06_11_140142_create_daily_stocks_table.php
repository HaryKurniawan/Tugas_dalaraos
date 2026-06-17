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
        Schema::create('daily_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_pos_id')->constrained('produk_pos')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_terjual')->default(0);
            $table->integer('stok_sisa')->default(0);
            $table->enum('status', ['Aktif', 'Ditutup', 'Diberikan ke Karyawan'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_stocks');
    }
};

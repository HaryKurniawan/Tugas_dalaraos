<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supliers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_suplier');
            $table->string('kontak')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tipe')->default('Konsinyasi');
            $table->timestamps();
        });

        Schema::create('stok_barang_kerings', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('nama_barang');
            $table->foreignId('suplier_id')->constrained('supliers')->cascadeOnDelete();
            $table->integer('jumlah_stok')->default(0);
            $table->string('satuan')->default('pcs'); // pcs, renceng, dll
            $table->date('tanggal_expired')->nullable();
            $table->string('lokasi_penyimpanan')->nullable();
            $table->decimal('harga_beli', 10, 2)->default(0);
            $table->decimal('harga_jual', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_barang_kerings');
        Schema::dropIfExists('supliers');
    }
};

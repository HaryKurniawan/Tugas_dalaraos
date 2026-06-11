<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_katerings', function (Blueprint $table) {
            $table->uuid('id')->primary(); // We use UUID for orders usually or string ID
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pelanggan');
            $table->string('no_telpon');
            $table->date('tanggal_acara');
            $table->integer('jumlah_porsi');
            $table->text('alamat');
            $table->string('kecamatan')->nullable();
            
            // Assuming string for menu to keep historical record if menu deleted
            $table->string('menu'); 
            $table->foreignId('menu_id')->nullable()->constrained('menus')->nullOnDelete();
            
            $table->decimal('total', 12, 2);
            $table->decimal('ongkir', 10, 2)->default(0);
            $table->string('metode_kirim'); // gosend, ambil_sendiri, internal
            $table->text('catatan')->nullable();
            
            // Desserts
            $table->integer('sarikaya_qty')->default(0);
            $table->integer('puding_coklat_qty')->default(0);
            $table->integer('jeruk_qty')->default(0);
            $table->integer('pisang_qty')->default(0);
            
            // Payment and Status
            $table->string('bukti_transfer')->nullable();
            $table->string('status_pembayaran')->default('Menunggu Verifikasi Admin'); // Menunggu Verifikasi Admin, Lunas, Ditolak
            $table->text('catatan_admin')->nullable();
            $table->string('status_pesanan')->default('Draft'); // Draft, Diproses Dapur, Siap Dikirim, Selesai
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_katerings');
    }
};

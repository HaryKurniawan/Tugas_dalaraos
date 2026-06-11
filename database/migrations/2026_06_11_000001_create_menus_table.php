<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('nama_menu');
            $table->decimal('harga', 10, 2);
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->default('Katering'); // Katering, Dessert
            $table->string('badge')->nullable();
            $table->json('isi_paket')->nullable(); // Array of strings
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

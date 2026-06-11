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
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->enum('type', ['Dine-in', 'Take-away', 'Keringan', 'Lainnya'])->default('Dine-in');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('payment_method')->default('Cash'); // Cash, QRIS, Transfer
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_transactions');
    }
};

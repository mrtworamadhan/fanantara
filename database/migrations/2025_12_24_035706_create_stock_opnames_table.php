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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('opname_number')->unique(); // Nomor Ref: OP-2023...
            $table->foreignId('product_id')->constrained('products');
            $table->integer('system_qty'); // Stok menurut komputer sebelum opname
            $table->integer('actual_qty'); // Stok fisik hasil hitungan
            $table->integer('difference'); // Selisih (Actual - System)
            $table->text('notes')->nullable(); // Alasan: Rusak, Hilang, Ketemu, dll
            $table->foreignId('user_id')->constrained('users'); // Siapa yang opname
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};

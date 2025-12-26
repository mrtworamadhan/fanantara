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
        Schema::create('member_shu_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('accounting_period_id')->constrained()->cascadeOnDelete();
            
            // Bobot Modal: Saldo x Hari Mengendap
            $table->decimal('accumulated_modal_weight', 20, 2)->default(0);
            
            // Volume Transaksi: Total Belanja di Periode Ini
            $table->decimal('total_transaction_volume', 15, 2)->default(0);
            
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();

            // Indeks agar pencarian super cepat
            $table->unique(['member_id', 'accounting_period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_shu_snapshots');
    }
};

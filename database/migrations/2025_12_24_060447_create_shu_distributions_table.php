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
        // Tabel Header (Settingan per Tahun)
        Schema::create('shu_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accounting_period_id')->constrained('accounting_periods'); // Tahun Buku
            $table->decimal('total_shu', 15, 2); // Total Laba Bersih yg mau dibagi
            
            // Setting Persentase (Total harus 100% idealnya, tapi yg masuk ke anggota cuma sebagian)
            $table->integer('percentage_modal'); // % untuk Jasa Modal
            $table->integer('percentage_services'); // % untuk Jasa Usaha (Transaksi)
            $table->integer('percentage_reserves'); // % untuk Cadangan/Pengurus/Sosial (Sisa)

            // Nilai Rupiahnya
            $table->decimal('amount_modal', 15, 2); 
            $table->decimal('amount_services', 15, 2);
            
            $table->string('status')->default('draft'); // draft, processed
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel Detail (Jatah Per Anggota)
        Schema::create('shu_distribution_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shu_distribution_id')->constrained('shu_distributions')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members');
            
            // Data Penunjang (Snapshot saat kalkulasi)
            $table->decimal('total_savings', 15, 2); // Total Simpanan dia saat itu
            $table->decimal('total_purchases', 15, 2); // Total Belanja dia saat itu
            
            // Hasil Hitungan
            $table->decimal('shu_modal', 15, 2); // Dapat berapa dari Jasa Modal
            $table->decimal('shu_services', 15, 2); // Dapat berapa dari Jasa Usaha
            $table->decimal('total_received', 15, 2); // Total yg diterima (Modal + Usaha)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shu_distributions');
        Schema::dropIfExists('shu_distribution_details');
    }
};

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
        // 1. UPDATE PERORANGAN
        Schema::table('individual_profiles', function (Blueprint $table) {
            // Legalitas
            $table->string('ktp_image')->nullable();
            $table->string('npwp_image')->nullable();
            $table->string('kk_image')->nullable();
            $table->string('job_type')->nullable();
            $table->string('main_commodity')->nullable(); 
            // Demand Side
            // JSON: {"beras": "10kg", "minyak": "2L", "pupuk_urea": "50kg"}
            $table->json('consumption_profile')->nullable();
            // Production Capacity (Jika Produsen)
            // JSON: {"luas_lahan": "2 Ha", "estimasi_panen": "10 Ton", "siklus": "3 Bulan"}
            $table->json('production_profile')->nullable();
        });

        // 2. UPDATE INSTITUSI (KOPERASI/PT/CV)
        Schema::table('institution_profiles', function (Blueprint $table) {
            // Legalitas
            $table->string('nib_image')->nullable();
            $table->string('ahu_image')->nullable();
            $table->string('npwp_image')->nullable();

            // Skala Bisnis
            $table->integer('total_members')->default(0); // Jumlah anggota mereka
            $table->decimal('annual_turnover', 15, 2)->default(0); // Omset Tahunan

            // Rantai Pasok (Peran)
            // Produsen, Distributor, Retailer, Logistik
            $table->string('supply_chain_role')->nullable(); 

            // Kapasitas Logistik
            // JSON: {"gudang": "100 m2", "armada": "2 Pick up", "cold_storage": "Tidak Ada"}
            $table->json('logistics_capacity')->nullable();

            // Sertifikasi
            // JSON: ["Halal", "PIRT", "HACCP"]
            $table->json('certifications')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_profiles',function (Blueprint $table){
            $table->dropColumn([
                'ktp_image',
                'npwp_image',
                'kk_image',
                'job_type',
                'main_commodity',
                'consumption_profile',
                'production_profile'
            ]);
        });
        Schema::table('institution_profiles',function (Blueprint $table){
            $table->dropColumn([
                'nib_image',
                'ahu_image',
                'npwp_image',
                'total_members',
                'annual_turnover',
                'supply_chain_role',
                'logistics_capacity',
                'certifications'
            ]);
        });
    }
};

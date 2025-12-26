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
        Schema::table('shu_distributions', function (Blueprint $table) {
            $table->dropColumn(['percentage_modal', 'percentage_services', 'percentage_reserves', 'amount_modal', 'amount_services']);
            $table->json('allocation_results')->nullable(); 
        });
        Schema::table('shu_distribution_details', function (Blueprint $table) {
            $table->dropColumn(['shu_modal', 'shu_services']);
            
            $table->json('distribution_breakdown')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

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
            $table->decimal('tax_amount', 15, 2)->default(0)->after('total_shu');
            $table->decimal('other_expenses', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('net_shu_to_distribute', 15, 2)->default(0)->after('other_expenses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shu_distributions', function (Blueprint $table) {
            $table->dropColumn(['tax_amount', 'other_expenses', 'net_shu_to_distribute']);
        });
    }
};

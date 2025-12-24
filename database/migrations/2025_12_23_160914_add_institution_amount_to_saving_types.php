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
        Schema::table('saving_types', function (Blueprint $table) {
            $table->renameColumn('default_amount', 'amount_individual');
            
            $table->decimal('amount_institution', 15, 2)->default(0)->after('amount_individual');
        });
    }

    public function down(): void
    {
        Schema::table('saving_types', function (Blueprint $table) {
            $table->renameColumn('amount_individual', 'default_amount');
            $table->dropColumn('amount_institution');
        });
    }
};

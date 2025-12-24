<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });

        Schema::table('individual_profiles', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('full_name');
        });

        Schema::table('institution_profiles', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('company_name');
        });
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn('image'));
        Schema::table('individual_profiles', fn (Blueprint $table) => $table->dropColumn('picture'));
        Schema::table('institution_profiles', fn (Blueprint $table) => $table->dropColumn('logo'));
    }
};

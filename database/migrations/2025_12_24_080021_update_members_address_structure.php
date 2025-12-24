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
        Schema::table('members', function (Blueprint $table) {

            $table->string('province_code', 2)->nullable()->index();
            $table->string('city_code', 5)->nullable()->index();
            $table->string('district_code', 8)->nullable()->index();
            $table->string('village_code', 13)->nullable()->index();
            
            $table->text('street_address')->nullable(); // Nama Jalan/RT/RW
            $table->longText('digital_signature')->nullable(); // TTD Digital
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members',function (Blueprint $table){
            $table->dropColumn('province_code');
            $table->dropColumn('city_code');
            $table->dropColumn('district_code');
            $table->dropColumn('village_code');
            $table->dropColumn('street_address');
            $table->dropColumn('digital_signature');
            
        });
    }
};

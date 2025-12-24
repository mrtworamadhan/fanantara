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
        Schema::create('institution_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('legal_number')->unique()->nullable();
            $table->string('nib')->nullable();
            $table->string('npwp', 20)->nullable();
            
            $table->string('pic_name'); 
            $table->string('pic_phone');
            $table->string('pic_position')->nullable();
            
            $table->text('address_office')->nullable();
            $table->date('establishment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_profiles');
    }
};

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
        Schema::create('individual_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('full_name');
            $table->string('place_of_birth')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('phone')->nullable();
            $table->text('address_ktp')->nullable();
            $table->string('mother_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_profiles');
    }
};

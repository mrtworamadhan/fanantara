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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('member_number')->unique()->nullable(); 
            $table->enum('type', ['individual', 'institution']);
            $table->enum('status', ['pending', 'active', 'rejected', 'suspended'])->default('pending');
            
            $table->unsignedBigInteger('profileable_id');
            $table->string('profileable_type');
            
            $table->date('join_date')->nullable();
            $table->string('referral_code')->nullable();
            $table->timestamps();
            
            $table->index(['profileable_id', 'profileable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

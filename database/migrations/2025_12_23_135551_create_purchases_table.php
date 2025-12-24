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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number')->unique();
            
            $table->foreignId('supplier_id')->constrained('members');
            
            $table->foreignId('warehouse_id')->constrained('warehouses');
            
            $table->date('purchase_date');
            $table->enum('status', ['draft', 'ordered', 'received', 'cancelled'])->default('draft');
            
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

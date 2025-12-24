<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saving_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('code')->unique();
            
            $table->enum('category', ['equity', 'liability']); // Ekuitas (Modal) atau Hutang
            $table->decimal('default_amount', 15, 2)->default(0); // Jika ada nilai fix (misal Wajib 50rb)
            $table->boolean('is_withdrawable')->default(true); // Pokok & Wajib biasanya False [cite: 80]
            
            $table->timestamps();
        });

        // 2. REKENING SIMPANAN ANGGOTA (Wadah Saldo)
        Schema::create('saving_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('saving_type_id')->constrained('saving_types');
            
            $table->string('account_number')->unique(); // No Rek: 001-SP-2024
            $table->decimal('balance', 15, 2)->default(0); // Saldo Saat Ini
            
            $table->timestamps();
            
            // Satu member cuma boleh punya 1 akun untuk jenis simpanan yang sama
            $table->unique(['member_id', 'saving_type_id']);
        });

        // 3. TRANSAKSI SIMPANAN (Setor/Tarik)
        Schema::create('saving_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saving_account_id')->constrained('saving_accounts');
            
            $table->enum('type', ['deposit', 'withdrawal', 'interest']); // Setor, Tarik, Bunga
            $table->decimal('amount', 15, 2);
            
            $table->date('transaction_date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saving_transactions');
        Schema::dropIfExists('saving_accounts');
        Schema::dropIfExists('saving_types');
    }
};
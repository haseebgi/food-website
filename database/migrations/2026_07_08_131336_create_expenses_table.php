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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            
            // Relationship with Category Table
            $table->foreignId('expense_category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Tracking and Amount
            $table->string('expense_number')->unique(); // Professional Serial No (e.g., EXP-2026-0001)
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');

            // Payment Mode
            $table->enum('payment_method', ['Cash', 'Card', 'JazzCash', 'EasyPaisa', 'Bank Transfer'])->default('Cash');
            $table->string('reference_number')->nullable(); // Bill Number ya Transaction ID tracking ke liye

            // Notes and Proofs
            $table->text('notes')->nullable();
            $table->string('receipt_image')->nullable(); // Bill/Voucher photo upload

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
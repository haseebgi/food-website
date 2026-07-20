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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Customer Relationship
            $table->foreignId('customer_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Order Number
            $table->string('order_number')->unique();

            // Grand Total
            $table->decimal('total_amount', 10, 2)->default(0);

            // Added for Payment & Dynamic Outstanding Balance Flow
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);

            // Order Type
            $table->enum('order_type', [
                'Dine In',
                'Take Away',
                'Delivery'
            ])->default('Dine In');

            // Payment Method
            $table->enum('payment_method', [
                'Cash',
                'Card',
                'JazzCash',
                'EasyPaisa'
            ])->default('Cash');

            // 🔥 FIXED: Added 'Partial' option to allow partial payments without truncation error
            $table->enum('payment_status', [
                'Pending',
                'Partial',
                'Paid'
            ])->default('Pending');

            // Order Status
            $table->enum('status', [
                'Pending',
                'Preparing',
                'Completed',
                'Cancelled'
            ])->default('Pending');

            // Customer Notes
            $table->text('notes')->nullable();

            // ⬇️ UPDATED FRONTEND FLOW FIELDS (Name field ke sath) ⬇️
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
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

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('invoice_no')->unique();

            $table->decimal('total_amount', 10, 2)->default(0);

            $table->enum('payment_status', [
                'Pending',
                'Paid',
                'Partial'
            ])->default('Pending');

            $table->text('remarks')->nullable();

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
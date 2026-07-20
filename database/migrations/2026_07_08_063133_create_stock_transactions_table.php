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
        Schema::create('stock_transactions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // stock_in | stock_out
            $table->enum('type', ['stock_in', 'stock_out']);

            $table->integer('quantity');

            $table->decimal('unit_price', 10, 2)->default(0);

            $table->decimal('total_price', 10, 2)->default(0);

            $table->text('remarks')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
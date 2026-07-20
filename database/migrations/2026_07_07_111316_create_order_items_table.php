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
        Schema::create('order_items', function (Blueprint $table) {

            $table->id();

            // Order Relationship
            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Product Relationship
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Product Quantity
            $table->integer('quantity');

            // Product Price
            $table->decimal('price',10,2);

            // Quantity × Price
            $table->decimal('subtotal',10,2);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            // Relationship
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Product Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);

            // Inventory
            $table->integer('quantity')->default(0);
            $table->integer('min_stock')->default(5);

            // Status
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
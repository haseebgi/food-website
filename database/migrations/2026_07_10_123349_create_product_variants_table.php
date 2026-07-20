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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            // Foreign key linking to products table
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            $table->string('size'); // Small, Medium, Large, 250ml, 1L etc.
            $table->decimal('price', 10, 2); // Price for this specific size
            $table->integer('quantity')->default(0); // Stock for this specific size
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

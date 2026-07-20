<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->unsignedBigInteger('parent_id')->nullable()->after('id');
        $table->string('size')->nullable()->after('name');

        // Foreign key connection (Optional but good practice)
        $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['parent_id']);
        $table->dropColumn(['parent_id', 'size']);
    });
}
};

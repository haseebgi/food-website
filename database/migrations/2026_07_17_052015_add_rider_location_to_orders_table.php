<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('rider_lat', 10, 7)->nullable()->after('postal_code');
            $table->decimal('rider_lng', 10, 7)->nullable()->after('rider_lat');
            $table->timestamp('location_updated_at')->nullable()->after('rider_lng');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['rider_lat', 'rider_lng', 'location_updated_at']);
        });
    }
};
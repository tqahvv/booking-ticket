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
        Schema::create('popular_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->integer('display_order')->default(0);
            $table->foreignId('sample_vehicle_type_id')->nullable()->constrained('vehicle_types')->onDelete('set null');
            $table->foreignId('image_id')->nullable()->constrained('images')->onDelete('set null');
            $table->decimal('sample_price_from', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popular_routes');
    }
};

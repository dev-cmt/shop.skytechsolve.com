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
        Schema::create('product_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->foreignId('shipping_class_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('inside_city_rate', 10, 2)->default(0.00);
            $table->decimal('outside_city_rate', 10, 2)->default(0.00);
            $table->boolean('free_shipping')->default(false);
            $table->timestamps();

            $table->unique('product_id'); // One-to-one relationship
            $table->index('shipping_class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shippings');
    }
};

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
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku_prefix')->nullable();
            $table->text('description')->nullable();
            $table->json('specification')->nullable(); // JSON for structured specs
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('main_image')->nullable();
            $table->enum('stock_status', ['quantity', 'in_stock', 'out_of_stock', 'upcoming'])->default('quantity');
            $table->integer('total_stock')->default(0);
            $table->integer('stock_out')->default(1);
            $table->integer('alert_quantity')->nullable();
            $table->string('expire')->nullable();
            $table->enum('product_type', ['sale', 'hot', 'regular', 'trending'])->default('regular');
            $table->enum('visibility', ['public', 'private', 'schedule'])->default('public');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('has_variant')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Indexes for faster querying
            $table->index(['category_id', 'status']);
            $table->index(['product_type', 'status']);
            $table->index(['visibility', 'published_at']);
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

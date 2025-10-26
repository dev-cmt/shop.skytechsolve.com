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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('path');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->enum('type', ['image', 'video', 'document'])->default('image');
            $table->string('alt_text')->nullable();
            $table->integer('size')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index(['type', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

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
            $table->string('parent_type')->nullable(); // e.g., App\Models\Service, App\Models\Blog
            $table->unsignedBigInteger('parent_id'); // ID of the parent model
            $table->string('file_path')->nullable();
            $table->string('caption')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            // Index polymorphic relationship
            $table->index(['parent_type', 'parent_id']);
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

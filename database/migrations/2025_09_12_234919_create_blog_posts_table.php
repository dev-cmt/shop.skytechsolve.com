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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title')->index();
            $table->string('slug')->unique(); // Unique slug
            $table->longText('content')->nullable();
            $table->string('image_path')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['published', 'scheduled', 'draft'])->default('draft');
            $table->timestamp('published_date')->nullable();
            $table->timestamps();
            $table->softDeletes(); // For soft deletion
        
            // Add index faster lookups by author
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};

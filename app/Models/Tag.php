<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tags');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(fn($tag) => $tag->slug = Str::slug($tag->name));
        static::updating(fn($tag) => $tag->slug = Str::slug($tag->name));
    }
}

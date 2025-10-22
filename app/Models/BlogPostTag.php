<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPostTag extends Model
{
    use HasFactory;
    
    // If you want to allow mass assignment
    protected $fillable = [
        'blog_post_id',
        'tag_id',
    ];

    // Optional: If your pivot has timestamps
    public $timestamps = true;

    // Relationships (optional)
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}

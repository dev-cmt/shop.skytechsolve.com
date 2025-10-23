<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'status'
    ];

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(fn($category) => $category->slug = Str::slug($category->name));
        static::updating(fn($category) => $category->slug = Str::slug($category->name));
    }

}

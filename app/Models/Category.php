<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'image',
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
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(fn($category) => $category->slug = Str::slug($category->name));
        static::updating(fn($category) => $category->slug = Str::slug($category->name));
    }

}

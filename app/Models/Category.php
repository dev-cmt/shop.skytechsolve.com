<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'slug',
        'description',
        'status'
    ];

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->category_name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->category_name);
        });
    }
}

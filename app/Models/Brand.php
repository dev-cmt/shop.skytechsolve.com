<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'sort_order',
        'status'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(fn($brand) => $brand->slug = Str::slug($brand->name));
        static::updating(fn($brand) => $brand->slug = Str::slug($brand->name));
    }
}

<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Product extends Model
{
     use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'slug',
        'sku_prefix',
        'description',
        'specification',
        'category_id',
        'brand_id',
        'sale_price',
        'purchase_price',
        'main_image',
        'stock_status',
        'total_stock',
        'stock_out',
        'alert_quantity',
        'expire',
        'product_type',
        'visibility',
        'published_at',
        'views',
        'has_variant',
        'status',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'specification' => 'array',  // JSON field
        'published_at' => 'datetime',
        'views' => 'integer',
        'has_variant' => 'boolean',
        'status' => 'boolean',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function discount(): HasOne
    {
        return $this->hasOne(ProductDiscount::class);
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(ProductShipping::class);
    }
    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'parent');
    }

    // Attributes through variant items
    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'product_variant_items',
            'product_variant_id',
            'attribute_id'
        )->distinct();
    }

    // Attribute items through variants
    public function attributeItems()
    {
        return $this->hasManyThrough(
            AttributeItem::class,
            ProductVariantItem::class,
            'product_variant_id', // FK on variant items
            'id', // PK on attribute items
            'id', // PK on product (via variant)
            'attribute_item_id' // FK on variant items
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', 'public')->where('published_at', '<=', now());
    }

    public function scopeWithVariant($query)
    {
        return $query->where('has_variant', true);
    }

    // Accessors & Mutators
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/default-product.png');
    }

    public function getIsPublishedAttribute()
    {
        return $this->published_at && $this->published_at <= now();
    }

    public function getCurrentDiscountAttribute()
    {
        return $this->discounts()->where('status', true)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
    }


    protected static function boot(): void
    {
        parent::boot();

        static::creating(fn($product) => $product->slug = Str::slug($product->name));
        static::updating(fn($product) => $product->slug = Str::slug($product->name));
    }
}

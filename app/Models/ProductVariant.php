<?php
// app/Models/ProductVariant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sku', 'price', 'purchase_cost', 'quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_cost' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variantItems(): HasMany
    {
        return $this->hasMany(ProductVariantItem::class);
    }

    public function attributeItems()
    {
        return $this->belongsToMany(AttributeItem::class, 'product_variant_items')
                    ->withPivot('image')
                    ->withTimestamps();
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Accessors & Mutators
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : $this->product->thumbnail_url;
    }

    public function getVariantNameAttribute()
    {
        $attributes = $this->variantItems->map(function ($item) {
            return $item->attributeItem->name;
        });

        return $attributes->implode(' / ');
    }

    public function getFinalPriceAttribute()
    {
        // Check if there's a variant-specific discount
        $discount = $this->product->currentDiscount;

        if ($discount) {
            if ($discount->discount_type === 'percentage') {
                return $this->price - ($this->price * $discount->amount / 100);
            } else {
                return max(0, $this->price - $discount->amount);
            }
        }

        return $this->price;
    }
}

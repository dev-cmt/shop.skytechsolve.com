<?php
// app/Models/ProductVariantItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id', 'attribute_id', 'attribute_item_id', 'image'
    ];

    // Relationships
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeItem(): BelongsTo
    {
        return $this->belongsTo(AttributeItem::class);
    }

    // Accessors & Mutators
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : $this->attributeItem->image_url;
    }
}

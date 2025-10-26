<?php
// app/Models/ProductShipping.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductShipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'weight', 'length', 'width', 'height',
        'shipping_class_id', 'inside_city_rate', 'outside_city_rate', 'free_shipping'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'inside_city_rate' => 'decimal:2',
        'outside_city_rate' => 'decimal:2',
        'free_shipping' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function shippingClass(): BelongsTo
    {
        return $this->belongsTo(ShippingClass::class);
    }

    // Accessors & Mutators
    public function getVolumeAttribute()
    {
        if ($this->length && $this->width && $this->height) {
            return $this->length * $this->width * $this->height;
        }
        return 0;
    }

    public function getDimensionalWeightAttribute()
    {
        if ($this->volume > 0) {
            return $this->volume / 5000; // Standard dimensional weight divisor
        }
        return 0;
    }

    public function getShippingRateAttribute()
    {
        if ($this->free_shipping) {
            return 0;
        }

        // You can implement logic to determine city vs outside city rates
        return [
            'inside_city' => $this->inside_city_rate,
            'outside_city' => $this->outside_city_rate,
        ];
    }
}

<?php
// app/Models/ProductDiscount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'discount_type', 'amount', 'start_date', 'end_date', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', true)
                    ->where('start_date', '>', now());
    }

    // Accessors & Mutators
    public function getIsActiveAttribute()
    {
        return $this->status &&
               $this->start_date <= now() &&
               $this->end_date >= now();
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->is_active) {
            return now()->diffInDays($this->end_date);
        }
        return 0;
    }

    public function calculateDiscount($price)
    {
        if ($this->discount_type === 'percentage') {
            return $price * ($this->amount / 100);
        }

        return $this->amount;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'sku',
        'quantity',
        'purchase_price',
        'sale_price',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array', // auto convert JSON <-> array
    ];

    // 🔗 Each item belongs to one order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 🔗 Each item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

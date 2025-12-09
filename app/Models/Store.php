<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'code',
        'phone',
        'email',
        'address',
        'logo',
        'status',
    ];

    // One store has many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // If you want store -> products relationship
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

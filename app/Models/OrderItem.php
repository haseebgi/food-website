<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

protected $fillable = [
    'order_id',
    'product_id',
    'quantity',
    'price',
    'subtotal',
];

    /**
     * Order Item belongs to Order
     * Explicitly defined foreign key 'order_id' for solid architecture
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Order Item belongs to Product
     * Explicitly defined foreign key 'product_id' for dynamic product mapping
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
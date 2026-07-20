<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [

        'purchase_id',
        'product_id',
        'quantity',
        'cost_price',
        'subtotal',

    ];

    /**
     * Purchase Item belongs to Purchase
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Purchase Item belongs to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [

        'product_id',
        'supplier_id',
        'type',
        'quantity',
        'unit_price',
        'total_price',
        'remarks',

    ];

    /**
     * Stock Transaction belongs to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Stock Transaction belongs to Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
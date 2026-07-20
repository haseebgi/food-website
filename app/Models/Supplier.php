<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'company_name',
    'phone',
    'email',
    'address',
    'status',
])]

class Supplier extends Model
{
    use HasFactory;

    /**
     * A Supplier can supply many Products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
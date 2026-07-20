<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', // 💡 Mass assignment protection mein parent_id lazmi add hona chahiye
        'category_id',
        'supplier_id',
        'name',
        'size', // 💡 Size array field track mapping add ho gai
        'slug',
        'image',
        'description',
        'cost_price',
        'selling_price',
        'quantity',
        'min_stock',
        'status',
    ];

    // Product belongs to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A Product belongs to one Supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    // Product has many Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * 💡 Self-Referencing Relation: Ek main product ke kayi sizes (child rows) ho sakte hain
     */
    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    /**
     * 💡 Self-Referencing Relation: Ek size product row ka ek hi main parent product hota hai
     */
    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id', 'id');
    }
}
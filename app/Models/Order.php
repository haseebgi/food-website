<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'order_number', 'total_amount', 'paid_amount',
        'due_amount', 'order_type', 'payment_method', 'payment_status',
        'status', 'notes', 'name', 'phone', 'email', 'address', 'city', 'postal_code',
        // ✅ Naye fields - live location tracking ke liye
        'rider_lat', 'rider_lng', 'location_updated_at',
    ];

    protected $casts = [
        'location_updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ✅ Ye asal relationship hai jo aap ki database use kar rahi hai (order_items table)
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}

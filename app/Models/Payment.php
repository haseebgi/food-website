<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'notes'
    ];

    // Order ke sath dynamic relationship
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
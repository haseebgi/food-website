<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Ye import sahi hai

class Customer extends Model
{
    use HasFactory, SoftDeletes; // ⚠️ Yahan 'SoftDeletes' add karna lazmi hai!

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        // 'deleted_at' ko fillable mein dalne ki zaroorat nahi hoti
    ];

    // Customer has many Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
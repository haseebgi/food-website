<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [

        'supplier_id',
        'invoice_no',
        'total_amount',
        'payment_status',
        'remarks',

    ];

    /**
     * Purchase belongs to Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Purchase has many Purchase Items
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
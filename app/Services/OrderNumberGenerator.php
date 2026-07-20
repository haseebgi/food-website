<?php

namespace App\Services;

use App\Models\Order;

// ✅ Sirf order number generate karne ka kaam
// Kal agar order number ka format change karna ho (jaise prefix,
// date-based number waghera), sirf isi class ko edit karo.
class OrderNumberGenerator
{
    public function generate(): string
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(uniqid());
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    // GET /api/orders/{order_number}
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'error'   => 'Order not found',
            ], 404);
        }

        // Agar aap ke Order model mein items ka relation defined hai
        // (e.g. public function orderItems() { return $this->hasMany(OrderItem::class); })
        // to yahan load kar lein taake order items bhi response mein aa jayen:
        // $order->load('orderItems');

        return response()->json([
            'success' => true,
            'order'   => $order,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreApiCheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutOrderService;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $checkoutOrderService;

    public function __construct(CartService $cartService, CheckoutOrderService $checkoutOrderService)
    {
        $this->cartService = $cartService;
        $this->checkoutOrderService = $checkoutOrderService;
    }

    // POST /api/checkout
    // Body: { name, phone, address, city, payment_method, items: [...] }
    public function store(StoreApiCheckoutRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']); // items alag nikal liye, baaki customer/order data hai

        // ⚠️ IMPORTANT: client (mobile app) ki bheji hui price kabhi trust nahi karte.
        // Yahan dobara DB se price/stock nikal ke asli cart banaya ja raha hai.
        $cart = $this->cartService->buildCartFromItems($items);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'error'   => 'Your crate is empty or items invalid hain.',
            ], 422);
        }

        try {
            // Doosra parameter ($cart) diya hai, is liye CheckoutOrderService
            // session wala cart use nahi karega, yahi diya hua array use karega.
            $order = $this->checkoutOrderService->placeOrder($data, $cart);

            return response()->json([
                'success'      => true,
                'message'      => 'Order placed successfully!',
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 422);
        }
    }
}

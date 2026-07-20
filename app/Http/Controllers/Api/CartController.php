<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

// ✅ Mobile app ka apna session nahi hota (web browser jaisa), is liye
// yeh controller "stateless" hai — mobile app apna local cart (jo app
// ke andar ya local storage mein hai) yahan bhejega, aur server DB se
// price/stock check kar ke asli totals wapas bhej dega.
class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // POST /api/cart/calculate
    // Body: { "items": [ { "product_id": 5, "variant_id": null, "quantity": 2 }, ... ] }
    public function calculate(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        // Yeh naya method CartService mein add karna hai (neeche diya gaya hai)
        $cart = $this->cartService->buildCartFromItems($request->input('items'));

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'error'   => 'No valid items found. Product IDs check karein.',
            ], 422);
        }

        $totals = $this->cartService->calculateTotals($cart);

        return response()->json([
            'success'  => true,
            'items'    => array_values($cart),
            'subtotal' => $totals['subtotal'],
            'delivery' => $totals['deliveryCharge'],
            'total'    => $totals['totalAmount'],
        ]);
    }
}

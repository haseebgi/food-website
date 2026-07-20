<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

// ✅ Sirf stock (product quantity) ka kaam yahan hoga.
// Order create/update/delete se koi lena dena nahi.
class StockService
{
    // Order item banao aur product ka stock kam karo
    public function reserveStock(Order $order, int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);

        if ($product->quantity < $quantity) {
            throw new \Exception("Insufficient stock for product: {$product->name}");
        }

        $order->items()->create([
            'product_id' => $product->id,
            'quantity'   => $quantity,
            'price'      => $product->selling_price,
            'subtotal'   => $product->selling_price * $quantity,
        ]);

        $product->decrement('quantity', $quantity);
    }

    // Order cancel/update/delete hone par stock wapas add karo
    public function restoreStockForOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('quantity', $item->quantity);
            }
        }
    }
}

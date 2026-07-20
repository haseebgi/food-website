<?php

namespace App\Services;

use App\Models\Product;

// ✅ Sirf product stock (quantity) badhane/kam karne ka kaam yahan hoga.
// Kal agar stock ka rule change ho (jaise negative stock allow karna),
// sirf isi class ko edit karo. (OCP)
class PurchaseStockService
{
    // Naya purchase aane par stock barhao
    public function increaseStock(int $productId, float $quantity): void
    {
        $product = Product::find($productId);
        if ($product) {
            $product->increment('quantity', $quantity);
        }
    }

    // Purchase delete hone par stock wapas kam karo (revert)
    // Agar stock kaafi na ho to exception throw karo
    public function decreaseStock(int $productId, float $quantity): void
    {
        $product = Product::find($productId);

        if (!$product || $product->quantity < $quantity) {
            $name = $product->name ?? 'Unknown';
            throw new \Exception("Cannot delete purchase! Product '{$name}' doesn't have enough stock to revert.");
        }

        $product->decrement('quantity', $quantity);
    }

    // Delete se pehle check karo ke saare items ka stock revert karne ke liye kaafi hai
    public function ensureSufficientStock(int $productId, float $quantity): void
    {
        $product = Product::find($productId);

        if (!$product || $product->quantity < $quantity) {
            $name = $product->name ?? 'Unknown';
            throw new \Exception("Cannot delete purchase! Product '{$name}' doesn't have enough stock to revert.");
        }
    }
}

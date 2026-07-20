<?php

namespace App\Services;

use App\Models\Product;

// ✅ Sirf session cart ka kaam yahan hoga (add/update/remove/totals).
// Kal agar cart ka storage tareeqa change ho (session ki jagah DB
// wala persistent cart), sirf isi class ko edit karo. (OCP)
class CartService
{
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    public function calculateTotals(array $cart): array
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $deliveryCharge = ($subtotal > 2000 || $subtotal == 0) ? 0 : 100;
        $totalAmount = $subtotal + $deliveryCharge;

        return [
            'subtotal'       => $subtotal,
            'deliveryCharge' => $deliveryCharge,
            'totalAmount'    => $totalAmount,
        ];
    }

    public function addItem(int $productId, ?int $variantId, int $quantity): ?array
    {
        $product = Product::find($productId);
        if (!$product) {
            return null;
        }

        $price = $product->selling_price;
        $sizeName = null;

        if ($variantId) {
            $variant = Product::find($variantId);
            if ($variant) {
                $price = $variant->selling_price;
                $sizeName = $variant->size;
            }
        }

        $cartKey = $variantId ? $product->id . '-' . $variantId : $product->id;
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'variant_id' => $variantId ?: null,
                'name'       => $sizeName ? $product->name . ' (' . ucfirst($sizeName) . ')' : $product->name,
                'quantity'   => $quantity,
                'price'      => $price,
                'image'      => $product->image,
                'category'   => $product->category->name ?? 'Fresh Produce',
            ];
        }

        session()->put('cart', $cart);

        return $cart;
    }

    public function updateItemQuantity(string $cartKey, int $quantity): array
    {
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return $cart;
    }

    public function removeItem(string $cartKey): array
    {
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        return $cart;
    }

    public function getTotalItemCount(array $cart): int
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    // =====================================================================
    // 🆕 NAYA METHOD — API / Mobile app ke liye (session use nahi karta)
    // =====================================================================
    // Mobile app apna local cart items (product_id, variant_id, quantity)
    // bhejega, yeh method DB se asli price nikal ke ek cart array bana kar
    // return karega — bilkul addItem() jaisa logic, sirf session ke bina.
    public function buildCartFromItems(array $items): array
    {
        $cart = [];

        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $variantId = $item['variant_id'] ?? null;
            $quantity  = max(1, intval($item['quantity'] ?? 1));

            if (!$productId) {
                continue;
            }

            $product = Product::find($productId);
            if (!$product) {
                continue; // fake/invalid product id ko silently skip kar dete hain
            }

            $price = $product->selling_price;
            $sizeName = null;

            if ($variantId) {
                $variant = Product::find($variantId);
                if ($variant) {
                    $price = $variant->selling_price;
                    $sizeName = $variant->size;
                }
            }

            $cartKey = $variantId ? $product->id . '-' . $variantId : $product->id;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $quantity;
            } else {
                $cart[$cartKey] = [
                    'product_id' => $product->id,
                    'variant_id' => $variantId ?: null,
                    'name'       => $sizeName ? $product->name . ' (' . ucfirst($sizeName) . ')' : $product->name,
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'image'      => $product->image,
                    'category'   => $product->category->name ?? 'Fresh Produce',
                ];
            }
        }

        return $cart;
    }
}

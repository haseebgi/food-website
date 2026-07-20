<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;

// ✅ Sirf purchase items banane aur grand total calculate karne ka kaam.
// Kal agar total mai tax/discount add karna ho, sirf isi class ko edit karo. (OCP)
class PurchaseItemService
{
    public function calculateGrandTotal(array $productIds, array $quantities, array $costPrices): float
    {
        $grandTotal = 0;

        foreach ($productIds as $key => $id) {
            $qty = (float) ($quantities[$key] ?? 0);
            $price = (float) ($costPrices[$key] ?? 0);
            $grandTotal += ($qty * $price);
        }

        return $grandTotal;
    }

    public function createItems(Purchase $purchase, array $productIds, array $quantities, array $costPrices): void
    {
        foreach ($productIds as $key => $productId) {
            $qty = (float) $quantities[$key];
            $price = (float) $costPrices[$key];

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id'  => $productId,
                'quantity'    => $qty,
                'cost_price'  => $price,
                'subtotal'    => $qty * $price,
            ]);
        }
    }
}

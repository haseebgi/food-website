<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected PurchaseStockService $stockService;
    protected PurchaseItemService $itemService;

    public function __construct(
        PurchaseStockService $stockService,
        PurchaseItemService $itemService
    ) {
        $this->stockService = $stockService;
        $this->itemService = $itemService;
    }

    public function getAllPurchases()
    {
        return Purchase::with('supplier')->latest()->get();
    }

    public function getCreationData()
    {
        return [
            'suppliers' => Supplier::where(DB::raw('LOWER(status)'), 'active')->get(),
            'products'  => Product::where('status', 1)->get(),
        ];
    }

    public function createPurchase(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            $grandTotal = $this->itemService->calculateGrandTotal(
                $data['product_id'],
                $data['quantity'],
                $data['cost_price']
            );

            $purchase = Purchase::create([
                'supplier_id'    => $data['supplier_id'],
                'invoice_no'     => $data['invoice_no'],
                'total_amount'   => $grandTotal,
                'payment_status' => $data['payment_status'],
                'remarks'        => $data['remarks'] ?? null,
            ]);

            $this->itemService->createItems(
                $purchase,
                $data['product_id'],
                $data['quantity'],
                $data['cost_price']
            );

            foreach ($data['product_id'] as $key => $productId) {
                $this->stockService->increaseStock($productId, (float) $data['quantity'][$key]);
            }

            return $purchase;
        });
    }

    public function deletePurchase(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            $purchaseItems = PurchaseItem::where('purchase_id', $purchase->id)->get();

            // pehle check karo ke sab items ka stock revert karne ke liye kaafi hai
            foreach ($purchaseItems as $item) {
                $this->stockService->ensureSufficientStock($item->product_id, $item->quantity);
            }

            // ab safely stock kam karo aur items delete karo
            foreach ($purchaseItems as $item) {
                $this->stockService->decreaseStock($item->product_id, $item->quantity);
                $item->delete();
            }

            $purchase->delete();
        });
    }
}

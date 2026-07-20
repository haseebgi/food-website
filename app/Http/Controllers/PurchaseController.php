<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Services\PurchaseService;
use App\Http\Requests\StorePurchaseRequest;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index()
    {
        $purchases = $this->purchaseService->getAllPurchases();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $data = $this->purchaseService->getCreationData();
        return view('purchases.create', $data);
    }

    public function store(StorePurchaseRequest $request)
    {
        try {
            $this->purchaseService->createPurchase($request->validated());

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase Added Successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        //
    }

    public function edit(Purchase $purchase)
    {
        //
    }

    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    public function destroy(Purchase $purchase)
    {
        try {
            $this->purchaseService->deletePurchase($purchase);

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase deleted and stock reverted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('purchases.index')
                ->with('error', 'Something went wrong while deleting: ' . $e->getMessage());
        }
    }
}

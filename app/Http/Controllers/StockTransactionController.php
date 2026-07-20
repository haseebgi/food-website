<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $transactions = StockTransaction::with('product', 'supplier')
                        ->latest()
                        ->get();

        return view('stock_transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
        $products = Product::all();

        $suppliers = Supplier::all();

        return view('stock_transactions.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->type == 'stock_in') {

            $product->quantity += $request->quantity;

        } else {

            if ($product->quantity < $request->quantity) {

                return back()->with('error', 'Not enough stock available.');

            }

            $product->quantity -= $request->quantity;
        }

        $product->save();

        StockTransaction::create([

            'product_id' => $request->product_id,

            'supplier_id' => $request->supplier_id,

            'type' => $request->type,

            'quantity' => $request->quantity,

            'unit_price' => $request->unit_price,

            'total_price' => $request->quantity * $request->unit_price,

            'remarks' => $request->remarks,

        ]);

        return redirect()
            ->route('stock-transactions.index')
            ->with('success', 'Stock transaction saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockTransaction $stockTransaction)
    {
        //
    }
}

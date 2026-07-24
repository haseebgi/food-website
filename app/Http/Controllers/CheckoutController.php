<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        // 1. Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\-]+$/u'],
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|string'
        ], [
            'name.regex' => 'Name mein sirf alphabets (letters) allow hain, numbers ya special characters nahi.',
        ]);

        // 2. Totals Calculation
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }
        
        $deliveryCharge = $totalAmount > 2000 ? 0 : 100;
        $grandTotal = $totalAmount + $deliveryCharge;

        // DB Transaction
        DB::beginTransaction();
        try {
            
            // 3. Customer ID Handling - Updated to provide 0 if user is not logged in
            $customerId = auth()->check() ? auth()->id() : 0; 

            // 4. Create Order using Standard Properties
            $order = new Order();
            $order->customer_id = $customerId;
            
            // Clean Order Number Generator
            $order->order_number = 'FC-' . strtoupper(\Str::random(8));
            
            $order->total_amount = $grandTotal;
            $order->paid_amount = 0; 
            $order->due_amount = $grandTotal;
            $order->order_type = 'Delivery'; 
            
            // Map payment method
            $method = strtolower($request->payment_method);
            if ($method == 'cod' || $method == 'cash on delivery') {
                $order->payment_method = 'Cash';
            } else {
                $order->payment_method = in_array($method, ['card', 'jazzcash', 'easypaisa']) ? ucfirst($method) : 'Cash';
            }

            $order->payment_status = 'Pending';
            $order->status = 'Pending';
            
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->email = $request->email;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->postal_code = $request->postal_code;
            
            // Order ko save karein
            $order->save();

            // 5. Create Order Items Safely
            foreach ($cart as $productId => $details) {
                $p_id = isset($details['product_id']) ? $details['product_id'] : $productId;
                $itemSubtotal = $details['price'] * $details['quantity'];

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $p_id;
                $orderItem->quantity = $details['quantity'];
                $orderItem->price = $details['price'];
                $orderItem->subtotal = $itemSubtotal; 
                $orderItem->save();

                // Stock Management
                $product = Product::find($p_id);
                if ($product && isset($product->quantity)) {
                    $product->quantity = max(0, $product->quantity - $details['quantity']);
                    $product->save();
                }
            }

            DB::commit();

            // Clear Cart Session
            session()->forget('cart');

            return redirect()->route('order.success', $order->order_number)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            dd('Database Error: ' . $e->getMessage() . ' in Line: ' . $e->getLine());
        }
    }

    /**
     * Display the order success page.
     */
    public function success($order_number)
    {
        return view('frontend.success', compact('order_number'));
    }

    /**
     * Generate Invoice view.
     */
    public function invoice($order_number)
    {
        $order = Order::with(['items.product'])->where('order_number', $order_number)->firstOrFail();
        return view('frontend.invoice', compact('order'));
    }
}

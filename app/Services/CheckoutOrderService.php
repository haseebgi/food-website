<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// ✅ Ye class sirf checkout complete hone par order banane ka
// flow coordinate karti hai. Cart ka kaam CartService karta hai,
// payment method mapping PaymentMethodMapper karta hai.
class CheckoutOrderService
{
    protected CartService $cartService;
    protected PaymentMethodMapper $paymentMethodMapper;

    public function __construct(
        CartService $cartService,
        PaymentMethodMapper $paymentMethodMapper
    ) {
        $this->cartService = $cartService;
        $this->paymentMethodMapper = $paymentMethodMapper;
    }

    // 🆕 CHANGE: ek naya optional parameter $cartOverride add kiya hai.
    // - Web (StorefrontController) jab placeOrder($data) call karega (1 argument),
    //   to $cartOverride null hoga aur pehle jaisa session wala cart hi use hoga.
    // - API (mobile) jab placeOrder($data, $cart) call karega (2 arguments),
    //   to wahi diya hua $cart array use hoga, session ko touch nahi karega.
    public function placeOrder(array $data, ?array $cartOverride = null): Order
    {
        $cart = $cartOverride ?? $this->cartService->getCart();

        if (empty($cart)) {
            throw new \Exception('Your crate is empty.');
        }

        return DB::transaction(function () use ($data, $cart, $cartOverride) {
            $totals = $this->cartService->calculateTotals($cart);

            $isCod = $this->paymentMethodMapper->isCashOnDelivery($data['payment_method']);
            $mappedPaymentMethod = $this->paymentMethodMapper->map($data['payment_method']);

            $customerId = $this->resolveCustomerId($data);

            $orderNumber = 'FC-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

            $order = Order::create([
                'customer_id'     => $customerId,
                'order_number'    => $orderNumber,
                'total_amount'    => $totals['totalAmount'],
                'paid_amount'     => $isCod ? 0 : $totals['totalAmount'],
                'due_amount'      => $isCod ? $totals['totalAmount'] : 0,
                'order_type'      => 'Delivery',
                'payment_method'  => $mappedPaymentMethod,
                'payment_status'  => $isCod ? 'Pending' : 'Paid',
                'status'          => 'Pending',
                'notes'           => $data['notes'] ?? null,
                'name'            => $data['name'],
                'phone'           => $data['phone'],
                'email'           => $data['email'] ?? null,
                'address'         => $data['address'],
                'city'            => $data['city'],
                'postal_code'     => $data['postal_code'] ?? null,
            ]);

            foreach ($cart as $details) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $details['product_id'],
                    'quantity'   => $details['quantity'],
                    'price'      => $details['price'],
                    'subtotal'   => $details['price'] * $details['quantity'],
                ]);

                $product = Product::find($details['product_id']);
                if ($product && isset($product->quantity)) {
                    $product->quantity = max(0, $product->quantity - $details['quantity']);
                    $product->save();
                }
            }

            if (!$isCod) {
                Payment::create([
                    'order_id'       => $order->id,
                    'amount_paid'    => $totals['totalAmount'],
                    'payment_date'   => now(),
                    'payment_method' => $mappedPaymentMethod,
                    'notes'          => 'Online wallet transaction processed successfully.',
                ]);
            }

            // 🆕 Sirf session wala cart tha (web checkout) to hi session clear karo.
            // API se aaya hua cart session mein tha hi nahi, is liye kuch clear nahi karna.
            if ($cartOverride === null) {
                $this->cartService->clear();
            }

            return $order;
        });
    }

    private function resolveCustomerId(array $data): int
    {
        if (auth()->check()) {
            return auth()->id();
        }

        $customer = Customer::firstOrCreate(
            ['phone' => $data['phone']],
            [
                'name'    => $data['name'],
                'email'   => $data['email'] ?? null,
                'address' => $data['address'] . ', ' . $data['city'],
                'status'  => 1,
            ]
        );

        return $customer->id;
    }
}

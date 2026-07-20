<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

// ✅ Ab ye class sirf "order" ke flow ko coordinate karti hai.
// Stock ka kaam StockService karega, PDF ka kaam OrderPdfService,
// order number ka kaam OrderNumberGenerator karega.
// Laravel ka container in classes ko khud inject kar dega, kuch
// alag se register karne ki zarurat nahi.
class OrderService
{
    protected StockService $stockService;
    protected OrderNumberGenerator $orderNumberGenerator;
    protected OrderPdfService $orderPdfService;

    public function __construct(
        StockService $stockService,
        OrderNumberGenerator $orderNumberGenerator,
        OrderPdfService $orderPdfService
    ) {
        $this->stockService = $stockService;
        $this->orderNumberGenerator = $orderNumberGenerator;
        $this->orderPdfService = $orderPdfService;
    }

    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $products = $data['products'];
            $orderData = Arr::except($data, ['products']);

            $orderData['order_number'] = $this->orderNumberGenerator->generate();

            $order = Order::create($orderData);

            foreach ($products as $item) {
                $this->stockService->reserveStock($order, $item['product_id'], $item['quantity']);
            }

            return $order;
        });
    }

    public function updateOrder(Order $order, array $data)
    {
        return DB::transaction(function () use ($order, $data) {
            $products = $data['products'] ?? null;
            $orderData = Arr::except($data, ['products', '_token', '_method']);

            $order->update($orderData);

            if (is_array($products)) {
                // purane items ka stock wapas karo
                $this->stockService->restoreStockForOrder($order);
                $order->items()->delete();

                // naye items ke liye stock reserve karo
                foreach ($products as $item) {
                    $this->stockService->reserveStock($order, $item['product_id'], $item['quantity']);
                }
            }

            return $order;
        });
    }

    public function getAllOrders()
    {
        return Order::latest()->paginate(10);
    }

    public function getOrderCreationData()
    {
        return [
            'customers' => Customer::where('status', 1)->get(),
            'products'  => Product::where('status', 1)->where('quantity', '>', 0)->get(),
        ];
    }

    public function getEditData()
    {
        return [
            'customers' => Customer::where('status', 1)->get(),
            'products'  => Product::where('status', 1)->get(),
        ];
    }

    public function deleteOrder(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $this->stockService->restoreStockForOrder($order);
            $order->items()->delete();
            return $order->delete();
        });
    }

    public function quickUpdateOrderStatus($id, $status)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);
    }

    public function generateSingleOrderPdf($id)
    {
        return $this->orderPdfService->generateSingle($id);
    }

    public function generateAllOrdersPdf()
    {
        return $this->orderPdfService->generateAll();
    }
}

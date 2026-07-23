<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\LocationTrackingService;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    protected $orderService;
    protected $locationService;

    public function __construct(OrderService $orderService, LocationTrackingService $locationService)
    {
        $this->orderService = $orderService;
        $this->locationService = $locationService;
    }

    public function index() {
        return view('orders.index', ['orders' => $this->orderService->getAllOrders()]);
    }

    public function create() {
        return view('orders.create', $this->orderService->getOrderCreationData());
    }

    public function store(StoreOrderRequest $request): RedirectResponse {
        try {
            $this->orderService->createOrder($request->validated());
            return redirect()->route('orders.index')->with('success', 'Order Added Successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Order $order) {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order) {
        return view('orders.edit', array_merge(['order' => $order], $this->orderService->getEditData()));
    }

    public function update(Request $request, Order $order) {
        try {
            $this->orderService->updateOrder($order, $request->all());
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Order $order) {
        try {
            $this->orderService->deleteOrder($order);
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function quickUpdateStatus($id, $status) {
        try {
            $this->orderService->quickUpdateOrderStatus($id, $status);
            return redirect()->route('orders.index')->with('success', 'Status updated.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function downloadSinglePdf($id) {
        return $this->orderService->generateSingleOrderPdf($id)->download('order_'.$id.'.pdf');
    }

    public function downloadAllPdf() {
        return $this->orderService->generateAllOrdersPdf()->download('all_orders.pdf');
    }

    // ✅ Naya method - rider ke liye QR code page dikhata hai
    public function riderQr(Order $order) {
        $riderUrl = $this->locationService->getRiderTrackingUrl($order);
        return view('orders.rider_qr', compact('order', 'riderUrl'));
    }


 public function cancel($id)
{
    // user_id ki shart hata kar sirf id se order find karein
    $order = Order::findOrFail($id);

    // Check karein ke order pending ya confirmed ho tabhi cancel ho sake
    if (in_array(strtolower($order->status), ['pending', 'confirmed'])) {
        $order->status = 'Cancelled';
        $order->save();
        
        return redirect()->back()->with('success', 'Order has been cancelled successfully.');
    }

    return redirect()->back()->with('error', 'This order cannot be cancelled.');
}

}

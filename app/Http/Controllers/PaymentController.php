<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Services\PaymentService;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = $this->paymentService->getAllPayments();
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request, $order_id = null)
    {
        $id = $order_id ?? $request->get('order_id');

        $orders = $this->paymentService->getOrdersWithPendingBalance();
        $selectedOrder = $this->paymentService->findOrder($id);

        return view('payments.create', compact('orders', 'selectedOrder'));
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            $payment = $this->paymentService->createPayment($request->validated());

            return redirect()->route('payments.index')
                ->with('success', "Payment of Rs. {$payment->amount_paid} saved successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Payment $payment)
    {
        $payment->load('order.customer');
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $order = Order::findOrFail($payment->order_id);
        return view('payments.edit', compact('payment', 'order'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        try {
            $this->paymentService->updatePayment($payment, $request->validated());

            return redirect()->route('payments.index')
                ->with('success', 'Payment entry updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        try {
            $this->paymentService->deletePayment($payment);

            return redirect()->route('payments.index')
                ->with('success', 'Payment deleted and order balance reverted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

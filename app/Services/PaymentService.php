<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected OrderBalanceService $balanceService;

    public function __construct(OrderBalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function getAllPayments()
    {
        return Payment::with('order.customer')->latest()->get();
    }

    public function getOrdersWithPendingBalance()
    {
        return Order::with('customer')
            ->whereIn('payment_status', ['Pending', 'Partial'])
            ->latest()
            ->get();
    }

    public function findOrder($id)
    {
        return $id ? Order::with('customer')->find($id) : null;
    }

    public function createPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $order = Order::findOrFail($data['order_id']);
            $amount = (float) $data['amount_paid'];

            if ($amount > $order->due_amount) {
                throw new \Exception('Paid amount cannot be greater than Remaining Due (Rs. ' . number_format($order->due_amount, 2) . ')');
            }

            $payment = Payment::create([
                'order_id'       => $order->id,
                'amount_paid'    => $amount,
                'payment_date'   => $data['payment_date'],
                'payment_method' => $data['payment_method'] ?? 'Cash',
                'notes'          => $data['notes'] ?? null,
            ]);

            $this->balanceService->applyPayment($order, $amount);

            return $payment;
        });
    }

    public function updatePayment(Payment $payment, array $data): Payment
    {
        return DB::transaction(function () use ($payment, $data) {
            $order = Order::findOrFail($payment->order_id);
            $oldAmount = (float) $payment->amount_paid;
            $newAmount = (float) $data['amount_paid'];

            $projectedTotalPaid = ($order->paid_amount - $oldAmount) + $newAmount;

            if ($projectedTotalPaid > $order->total_amount) {
                throw new \Exception('Updated amount exceeds the total order amount!');
            }

            $payment->update([
                'amount_paid'    => $newAmount,
                'payment_date'   => $data['payment_date'],
                'payment_method' => $data['payment_method'] ?? $payment->payment_method,
                'notes'          => $data['notes'] ?? null,
            ]);

            $this->balanceService->adjustPayment($order, $oldAmount, $newAmount);

            return $payment;
        });
    }

    public function deletePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $order = Order::findOrFail($payment->order_id);
            $amount = (float) $payment->amount_paid;

            $this->balanceService->revertPayment($order, $amount);

            $payment->delete();
        });
    }
}

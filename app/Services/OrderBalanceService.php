<?php

namespace App\Services;

use App\Models\Order;

// ✅ Sirf order ka balance (paid/due amount) aur payment_status
// calculate/update karne ka kaam yahan hoga.
// Pehle ye "status determine karne wala" logic 3 jagah (store,
// update, destroy) copy-paste tha — ab sirf yahan ek jagah hai.
// Kal agar business rule change ho (jaise "Partial" ki definition),
// sirf isi class ko edit karo. (OCP)
class OrderBalanceService
{
    public function determineStatus(float $paidAmount, float $totalAmount): string
    {
        $remainingDue = $totalAmount - $paidAmount;

        if ($remainingDue <= 0) {
            return 'Paid';
        }

        if ($paidAmount > 0 && $remainingDue > 0) {
            return 'Partial';
        }

        return 'Pending';
    }

    // Naya payment add hone par order ka balance update karo
    public function applyPayment(Order $order, float $amount): void
    {
        $totalPaid = $order->paid_amount + $amount;
        $this->recalculate($order, $totalPaid);
    }

    // Payment amount edit hone par order ka balance dobara calculate karo
    public function adjustPayment(Order $order, float $oldAmount, float $newAmount): void
    {
        $totalPaid = ($order->paid_amount - $oldAmount) + $newAmount;
        $this->recalculate($order, $totalPaid);
    }

    // Payment delete hone par order ka balance wapas piche le jao
    public function revertPayment(Order $order, float $amount): void
    {
        $totalPaid = $order->paid_amount - $amount;
        $this->recalculate($order, $totalPaid);
    }

    private function recalculate(Order $order, float $totalPaid): void
    {
        $remainingDue = $order->total_amount - $totalPaid;
        $status = $this->determineStatus($totalPaid, $order->total_amount);

        $order->update([
            'paid_amount'    => $totalPaid,
            'due_amount'     => $remainingDue,
            'payment_status' => $status,
        ]);
    }
}

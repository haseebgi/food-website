<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

// ✅ Sirf PDF banane ka kaam yahan hoga.
// Kal agar DomPDF ki jagah koi aur PDF library use karni ho,
// sirf yahi class change hogi — OrderService ko haath nahi lagana parega (OCP)
class OrderPdfService
{
    public function generateSingle($id)
    {
        $order = Order::with('customer', 'items.product')->findOrFail($id);
        return Pdf::loadView('orders_single_pdf', compact('order'));
    }

    public function generateAll()
    {
        $orders = Order::with('items.product')->get();
        return Pdf::loadView('orders_pdf', compact('orders'));
    }
}

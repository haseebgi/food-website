<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('🔔 Security Alert: Order Delivered via Your Email — FreshCrate')
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #e8f5e9;'>
                            <h2 style='color: #1b5e20;'>FreshCrate Order Notification</h2>
                            <p>Hi there,</p>
                            <p>This is a security notification regarding your email address.</p>
                            <div style='background-color: #f9f9f9; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0;'>
                                <p style='margin: 0;'><strong>Order Number:</strong> #{$this->order->order_number}</p>
                                <p style='margin: 5px 0 0 0;'><strong>Status:</strong> Delivered</p>
                                <p style='margin: 5px 0 0 0;'><strong>Delivery Address:</strong> {$this->order->address}</p>
                            </div>
                            <p>An order has been successfully placed and delivered using your email details. If this wasn't you, it's highly likely a friend or family member (like <strong>" . auth()->user()->name . "</strong>) ordered a delivery or gift for you to your address.</p>
                            <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                            <p style='font-size: 0.85rem; color: #777;'>Thank you for choosing FreshCrate Grocers.</p>
                        </div>
                    ");
    }
}
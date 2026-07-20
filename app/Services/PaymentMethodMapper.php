<?php

namespace App\Services;

// ✅ Sirf form ki raw value (cod, jazzcash, easypaisa) ko DB ke
// ENUM values (Cash, JazzCash, EasyPaisa) ke sath map karne ka kaam.
// Kal agar naya payment method (jaise 'sadapay') add karna ho,
// sirf yahan array mai ek line add karo — CheckoutOrderService ko
// haath nahi lagana parega. (OCP)
class PaymentMethodMapper
{
    protected array $map = [
        'cod'       => 'Cash',
        'jazzcash'  => 'JazzCash',
        'easypaisa' => 'EasyPaisa',
    ];

    public function map(string $rawMethod): string
    {
        $key = strtolower($rawMethod);
        return $this->map[$key] ?? 'Cash';
    }

    public function isCashOnDelivery(string $rawMethod): bool
    {
        return strtolower($rawMethod) === 'cod';
    }
}

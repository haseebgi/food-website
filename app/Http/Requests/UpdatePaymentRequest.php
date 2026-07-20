<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// ✅ Store se alag isliye banayi hai kyunke update ke waqt order_id
// dobara select/validate nahi karna (Payment already kisi order se linked hai)
class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount_paid'    => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'payment_method' => 'nullable|string',
            'notes'          => 'nullable|string',
        ];
    }
}

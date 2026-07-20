<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id'       => 'required|exists:orders,id',
            'amount_paid'    => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'payment_method' => 'nullable|string',
            'notes'          => 'nullable|string',
        ];
    }
}

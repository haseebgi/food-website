<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'    => 'required',
            'invoice_no'     => 'required|unique:purchases,invoice_no',
            'payment_status' => 'required',
            'product_id'     => 'required|array|min:1',
            'quantity'       => 'required|array',
            'cost_price'     => 'required|array',
        ];
    }
}

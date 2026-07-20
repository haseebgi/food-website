<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Request allow karne ke liye
    }

    public function rules(): array
    {
        // Yahan aapki saari validation rules ayengi
        return [
            'customer_id'     => 'required|exists:customers,id',
            'order_type'      => 'required|in:Dine In,Take Away,Takeaway,Online Delivery,Delivery',
            'payment_method'  => 'required',
            'payment_status'  => 'required',
            'total_amount'    => 'required|numeric|min:1',
            'products'        => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'name'            => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'postal_code'     => 'nullable|string|max:20',
        ];
    }
}
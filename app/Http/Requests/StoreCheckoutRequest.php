<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email',
            'phone'          => 'required|string',
            'address'        => 'required|string',
            'city'           => 'required|string',
            'payment_method' => 'required|string',
            'notes'          => 'nullable|string',
            'postal_code'    => 'nullable|string',
        ];
    }
}

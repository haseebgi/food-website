<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

// ✅ Yeh web wale StoreCheckoutRequest jaisa hi hai, sirf "items" array
// ki validation extra add ki gayi hai (mobile app se items yahin aate hain).
class StoreApiCheckoutRequest extends FormRequest
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

            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.quantity'   => 'required|integer|min:1',
        ];
    }
}

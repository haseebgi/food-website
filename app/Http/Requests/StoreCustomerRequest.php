<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// ✅ Sirf validation ka kaam yahan hoga.
// Controller ko validation rules likhne ki zarurat nahi.
class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|unique:customers,email',
            'phone'   => 'required|string|max:20|unique:customers,phone',
            'address' => 'nullable|string',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// ✅ Update ke liye alag validation — kyunke email/phone unique check
// karte waqt current customer ko khud se exclude karna hota hai.
class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // route se customer ka id nikal rahe hain (route model binding se)
        $customerId = $this->route('customer')->id;

        return [
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|unique:customers,email,' . $customerId,
            'phone'   => 'required|string|max:20|unique:customers,phone,' . $customerId,
            'address' => 'nullable|string',
        ];
    }
}

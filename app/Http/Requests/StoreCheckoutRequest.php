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
            // Sirf letters, spaces, dot, hyphen — numbers ya special characters allow nahi
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\-]+$/u'],

            // Standard email format check
            'email' => ['nullable', 'email', 'max:255'],

            // Sirf digits, 10 se 15 numbers ke beech (Pakistani mobile format jese 03XXXXXXXXX)
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],

            // Address mein letters, numbers, spaces, comma, dot, hyphen allow — kyunke house/street number hota hai
            'address' => ['required', 'string', 'max:500', 'regex:/^[\pL0-9\s\,\.\-\/]+$/u'],

            // City ka naam sirf letters mein hota hai
            'city' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\.\-]+$/u'],

            // Sirf defined payment methods allow
            'payment_method' => ['required', 'string', 'in:cod,jazzcash,easypaisa'],

            // Notes free text hai, koi restriction nahi
            'notes' => ['nullable', 'string', 'max:1000'],

            // Postal code sirf digits mein hota hai
            'postal_code' => ['nullable', 'string', 'regex:/^[0-9]{4,10}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex'         => 'Name can only contain letters and spaces.',
            'phone.regex'        => 'Phone number can only contain digits (0-9).',
            'address.regex'      => 'Address can only contain letters, numbers, commas, periods, and hyphens.',
            'city.regex'         => 'City name can only contain letters.',
            'postal_code.regex'  => 'Postal code must contain digits only.',
            'payment_method.in'  => 'Please select a valid payment method.',
        ];
    }
}

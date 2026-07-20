<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// ✅ Store se alag isliye banayi hai kyunke update ke waqt variants
// dobara create nahi hote (wo sirf naya product banate waqt hota hai)
class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description'   => 'nullable|string',
            'cost_price'    => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:0',
        ];
    }
}

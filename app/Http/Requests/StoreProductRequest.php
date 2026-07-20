<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            // Variants ki conditional validation (agar checkbox on ho)
            'variants'        => 'required_if:has_variants,1|array',
            'variants.*.size'  => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
        ];
    }
}

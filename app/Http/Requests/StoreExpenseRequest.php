<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0.01',
            'expense_date'        => 'required|date',
            'payment_method'      => 'required|in:Cash,Card,JazzCash,EasyPaisa,Bank Transfer',
            'reference_number'    => 'nullable|string|max:255',
            'notes'               => 'nullable|string',
            'receipt_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

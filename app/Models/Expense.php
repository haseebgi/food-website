<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_category_id',
        'expense_number',
        'amount',
        'expense_date',
        'payment_method',
        'reference_number',
        'notes',
        'receipt_image'
    ];

    // Expense belongs to a Category
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
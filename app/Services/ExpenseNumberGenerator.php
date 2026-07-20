<?php

namespace App\Services;

use App\Models\Expense;

// ✅ Sirf expense number generate karne ka kaam (e.g. EXP-2026-0001)
// Kal agar number ka format change karna ho, sirf isi class ko edit karo.
class ExpenseNumberGenerator
{
    public function generate(): string
    {
        $year = date('Y');
        $latestExpense = Expense::whereYear('created_at', $year)->latest()->first();

        if ($latestExpense) {
            $parts = explode('-', $latestExpense->expense_number);
            $sequence = intval(end($parts)) + 1;
        } else {
            $sequence = 1;
        }

        return 'EXP-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

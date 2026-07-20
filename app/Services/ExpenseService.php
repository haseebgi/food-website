<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseCategory;

// ✅ Ab ye class sirf expense ke flow ko coordinate karti hai.
// Number generation ka kaam ExpenseNumberGenerator karega,
// image ka kaam ReceiptImageService karega.
class ExpenseService
{
    protected ExpenseNumberGenerator $numberGenerator;
    protected ReceiptImageService $imageService;

    public function __construct(
        ExpenseNumberGenerator $numberGenerator,
        ReceiptImageService $imageService
    ) {
        $this->numberGenerator = $numberGenerator;
        $this->imageService = $imageService;
    }

    public function getAllExpenses()
    {
        return Expense::with('category')->latest()->get();
    }

    public function getActiveCategories()
    {
        return ExpenseCategory::where('is_active', true)->get();
    }

    public function createExpense(array $data, $receiptImageFile = null): Expense
    {
        $imagePath = null;
        if ($receiptImageFile) {
            $imagePath = $this->imageService->upload($receiptImageFile);
        }

        return Expense::create([
            'expense_category_id' => $data['expense_category_id'],
            'expense_number'      => $this->numberGenerator->generate(),
            'amount'              => $data['amount'],
            'expense_date'        => $data['expense_date'],
            'payment_method'      => $data['payment_method'],
            'reference_number'    => $data['reference_number'] ?? null,
            'notes'               => $data['notes'] ?? null,
            'receipt_image'       => $imagePath,
        ]);
    }

    public function updateExpense(Expense $expense, array $data, $receiptImageFile = null): Expense
    {
        $imagePath = $expense->receipt_image;

        if ($receiptImageFile) {
            $imagePath = $this->imageService->replace($expense->receipt_image, $receiptImageFile);
        }

        $expense->update([
            'expense_category_id' => $data['expense_category_id'],
            'amount'              => $data['amount'],
            'expense_date'        => $data['expense_date'],
            'payment_method'      => $data['payment_method'],
            'reference_number'    => $data['reference_number'] ?? null,
            'notes'               => $data['notes'] ?? null,
            'receipt_image'       => $imagePath,
        ]);

        return $expense;
    }

    public function deleteExpense(Expense $expense): void
    {
        $this->imageService->delete($expense->receipt_image);
        $expense->delete();
    }
}

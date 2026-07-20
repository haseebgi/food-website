<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\ExpenseService;
use App\Http\Requests\StoreExpenseRequest;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        $expenses = $this->expenseService->getAllExpenses();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = $this->expenseService->getActiveCategories();
        return view('expenses.create', compact('categories'));
    }

    // ✅ Note: store aur update ke validation rules bilkul same hain,
    // isliye ek hi StoreExpenseRequest dono jagah use kar rahe hain
    // (jaisa OrderController mai tha — jab rules same hon to 1 file kaafi hai)
    public function store(StoreExpenseRequest $request)
    {
        $this->expenseService->createExpense(
            $request->validated(),
            $request->file('receipt_image')
        );

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = $this->expenseService->getActiveCategories();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(StoreExpenseRequest $request, Expense $expense)
    {
        $this->expenseService->updateExpense(
            $expense,
            $request->validated(),
            $request->file('receipt_image')
        );

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->expenseService->deleteExpense($expense);

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense record deleted successfully.');
    }
}

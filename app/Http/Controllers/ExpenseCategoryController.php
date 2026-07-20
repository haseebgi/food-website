<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExpenseCategory::latest()->get();
        return view('expense_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string',
        ]);

        ExpenseCategory::create([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active') ? $request->is_active : true,
        ]);

        return redirect()->route('expense-categories.index')
                        ->with('success', 'Expense Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense_categories.edit', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string',
        ]);

        $expenseCategory->update([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active') ? $request->is_active : true,
        ]);

        return redirect()->route('expense-categories.index')
                        ->with('success', 'Expense Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Category delete karne se pehle check karenge ke isme koi kharcha to record nahi hai
        if ($expenseCategory->expenses()->count() > 0) {
            return redirect()->route('expense-categories.index')
                            ->with('error', 'Cannot delete category. It contains recorded expenses.');
        }

        $expenseCategory->delete();

        return redirect()->route('expense-categories.index')
                        ->with('success', 'Expense Category deleted successfully.');
    }
}
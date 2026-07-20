@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>All Expenses</h2>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Add New Expense</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Exp#</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date }}</td>
                            <td>{{ $expense->expense_number }}</td>
                            <td>{{ $expense->category->name }}</td>
                            <td>Rs. {{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->payment_method }}</td>
                            <td>
                                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-sm btn-info">View</a>
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No expenses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
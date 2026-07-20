@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="card shadow-sm" style="max-width: 700px; margin: auto;">
        <div class="card-body">
            <h4 class="mb-4">Edit Expense: {{ $expense->expense_number }}</h4>
            
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="expense_category_id" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $expense->expense_category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Amount (Rs) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            @foreach(['Cash', 'Card', 'JazzCash', 'EasyPaisa', 'Bank Transfer'] as $method)
                                <option {{ $expense->payment_method == $method ? 'selected' : '' }}>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Reference/Bill No</label>
                    <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $expense->reference_number) }}">
                </div>

                <div class="mb-3">
                    <label>Update Receipt (Optional)</label>
                    <input type="file" name="receipt_image" class="form-control">
                    @if($expense->receipt_image)
                        <small class="text-muted">Current: {{ $expense->receipt_image }}</small>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control">{{ old('notes', $expense->notes) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Expense</button>
            </form>
        </div>
    </div>
</div>
@endsection
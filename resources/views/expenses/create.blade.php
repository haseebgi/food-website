@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="card shadow-sm" style="max-width: 700px; margin: auto;">
        <div class="card-body">
            <h4 class="mb-4">Record New Expense</h4>
            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="expense_category_id" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Amount (Rs) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option>Cash</option>
                            <option>Card</option>
                            <option>JazzCash</option>
                            <option>EasyPaisa</option>
                            <option>Bank Transfer</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Reference/Bill No</label>
                    <input type="text" name="reference_number" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Receipt Image (Optional)</label>
                    <input type="file" name="receipt_image" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100">Save Expense</button>
            </form>
        </div>
    </div>
</div>
@endsection
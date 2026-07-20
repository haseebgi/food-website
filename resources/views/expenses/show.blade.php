@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="card shadow-sm" style="max-width: 800px; margin: auto;">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <span>Expense Details: {{ $expense->expense_number }}</span>
            <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Category:</strong> {{ $expense->category->name }}</p>
                    <p><strong>Amount:</strong> Rs. {{ number_format($expense->amount, 2) }}</p>
                    <p><strong>Date:</strong> {{ $expense->expense_date }}</p>
                    <p><strong>Method:</strong> {{ $expense->payment_method }}</p>
                    <p><strong>Reference:</strong> {{ $expense->reference_number ?? 'N/A' }}</p>
                    <p><strong>Notes:</strong> {{ $expense->notes ?? 'No notes added.' }}</p>
                </div>
                <div class="col-md-6 text-center">
                    @if($expense->receipt_image)
                        <label><strong>Receipt Image:</strong></label><br>
                        <img src="{{ asset('storage/' . $expense->receipt_image) }}" class="img-fluid rounded mt-2" style="max-height: 300px;">
                    @else
                        <div class="alert alert-warning">No receipt image attached.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="card shadow-sm" style="max-width: 800px; margin: auto;">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <span>Expense Details: {{ $expense->expense_number }}</span>
            <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Category:</strong> {{ $expense->category->name }}</p>
                    <p><strong>Amount:</strong> Rs. {{ number_format($expense->amount, 2) }}</p>
                    <p><strong>Date:</strong> {{ $expense->expense_date }}</p>
                    <p><strong>Method:</strong> {{ $expense->payment_method }}</p>
                    <p><strong>Reference:</strong> {{ $expense->reference_number ?? 'N/A' }}</p>
                    <p><strong>Notes:</strong> {{ $expense->notes ?? 'No notes added.' }}</p>
                </div>
                <div class="col-md-6 text-center">
                    @if($expense->receipt_image)
                        <label><strong>Receipt Image:</strong></label><br>
                        <img src="{{ asset('storage/' . $expense->receipt_image) }}" class="img-fluid rounded mt-2" style="max-height: 300px;">
                    @else
                        <div class="alert alert-warning">No receipt image attached.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
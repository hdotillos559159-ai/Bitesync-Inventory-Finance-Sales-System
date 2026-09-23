@extends('layouts.app')

@section('title', 'Edit expense — BiteSync')
@section('tagline', 'Edit expense')

@section('content')

  <div class="panel form-panel">
    <div class="head"><h2>Edit {{ $expense->expense_no }}</h2></div>
    <form action="{{ route('expenses.update', $expense) }}" method="POST" class="form">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="expense_no">Expense ID</label>
        <input type="text" id="expense_no" name="expense_no" value="{{ old('expense_no', $expense->expense_no) }}" required>
        @error('expense_no') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" id="category" name="category" value="{{ old('category', $expense->category) }}" required>
        @error('category') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="expense_date">Date</label>
          <input type="date" id="expense_date" name="expense_date"
                 value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
          @error('expense_date') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label for="recorded_by">Recorded by</label>
          <input type="text" id="recorded_by" name="recorded_by" value="{{ old('recorded_by', $expense->recorded_by) }}" required>
          @error('recorded_by') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group">
        <label for="purchase_id">Linked purchase (optional)</label>
        <select id="purchase_id" name="purchase_id">
          <option value="">— None —</option>
          @foreach ($purchases as $purchase)
            <option value="{{ $purchase->id }}" {{ (int) old('purchase_id', $expense->purchase_id) === $purchase->id ? 'selected' : '' }}>
              {{ $purchase->purchase_no }}
            </option>
          @endforeach
        </select>
        @error('purchase_id') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="amount">Amount (₱)</label>
        <input type="number" step="0.01" min="0" id="amount" name="amount" value="{{ old('amount', $expense->amount) }}" required>
        @error('amount') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-actions">
        <a class="btn btn-ghost" href="{{ route('expenses.index') }}">Cancel</a>
        <button type="submit" class="btn">Update expense</button>
      </div>
    </form>
  </div>

@endsection

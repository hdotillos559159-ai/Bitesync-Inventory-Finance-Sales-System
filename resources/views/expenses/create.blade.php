@extends('layouts.app')

@section('title', 'New expense — BiteSync')
@section('tagline', 'Record an expense')

@section('content')

  <div class="panel form-panel">
    <div class="head"><h2>New expense</h2></div>
    <form action="{{ route('expenses.store') }}" method="POST" class="form">
      @csrf

      <div class="form-group">
        <label for="expense_no">Expense ID</label>
        <input type="text" id="expense_no" name="expense_no" value="{{ old('expense_no') }}" placeholder="EX-0308" required>
        @error('expense_no') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="Ingredients / Utilities / Repairs / Supplies" required>
        @error('category') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="expense_date">Date</label>
          <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date') }}" required>
          @error('expense_date') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label for="recorded_by">Recorded by</label>
          <input type="text" id="recorded_by" name="recorded_by" value="{{ old('recorded_by') }}" placeholder="J. Cruz" required>
          @error('recorded_by') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group">
        <label for="purchase_id">Linked purchase (optional)</label>
        <select id="purchase_id" name="purchase_id">
          <option value="">— None —</option>
          @foreach ($purchases as $purchase)
            <option value="{{ $purchase->id }}" {{ (string) old('purchase_id') === (string) $purchase->id ? 'selected' : '' }}>
              {{ $purchase->purchase_no }}
            </option>
          @endforeach
        </select>
        @error('purchase_id') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="amount">Amount (₱)</label>
        <input type="number" step="0.01" min="0" id="amount" name="amount" value="{{ old('amount') }}" required>
        @error('amount') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-actions">
        <a class="btn btn-ghost" href="{{ route('expenses.index') }}">Cancel</a>
        <button type="submit" class="btn">Save expense</button>
      </div>
    </form>
  </div>

@endsection

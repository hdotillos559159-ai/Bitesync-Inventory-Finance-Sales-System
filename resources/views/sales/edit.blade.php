@extends('layouts.app')

@section('title', 'Edit sale — BiteSync')
@section('tagline', 'Edit sale')

@section('content')

  <div class="panel form-panel">
    <div class="head"><h2>Edit sale {{ $sale->sale_number }}</h2></div>
    <form action="{{ route('sales.update', $sale) }}" method="POST" class="form">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="sale_number">Sale number</label>
        <input type="text" id="sale_number" name="sale_number" value="{{ old('sale_number', $sale->sale_number) }}" required>
        @error('sale_number') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
         <label for="sale_date">Date &amp; time</label>
         <input type="datetime-local" id="sale_date" name="sale_date"
           value="{{ old('sale_date', $sale->sale_date->format('Y-m-d\TH:i')) }}" required>
         @error('sale_date') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="payment_method">Payment</label>
          <select id="payment_method" name="payment_method" required>
            <option value="cash" {{ old('payment_method', $sale->payment_method) === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="gcash" {{ old('payment_method', $sale->payment_method) === 'gcash' ? 'selected' : '' }}>GCash</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="discount">Discount (optional)</label>
        <input type="number" step="0.01" min="0" id="discount" name="discount" value="{{ old('discount', $sale->discount) }}" placeholder="0.00" inputmode="decimal">
        @error('discount') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
         <label for="total">Total amount (₱)</label>
         <input type="number" step="0.01" min="0" id="total" name="total"
           value="{{ old('total', $sale->total) }}" required>
         @error('total') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-actions">
        <a class="btn btn-ghost" href="{{ route('sales.index') }}">Cancel</a>
        <button type="submit" class="btn">Update sale</button>
      </div>
    </form>
  </div>

@endsection

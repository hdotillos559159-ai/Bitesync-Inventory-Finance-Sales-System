@extends('layouts.app')

@section('title', 'Edit purchase — BiteSync')
@section('tagline', 'Edit purchase order')

@section('content')

  <div class="panel form-panel">
    <div class="head"><h2>Edit {{ $purchase->purchase_no }}</h2></div>
    <form action="{{ route('purchases.update', $purchase) }}" method="POST" class="form">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="purchase_no">Purchase #</label>
        <input type="text" id="purchase_no" name="purchase_no" value="{{ old('purchase_no', $purchase->purchase_no) }}" required>
        @error('purchase_no') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="supplier_id">Supplier</label>
        <select id="supplier_id" name="supplier_id" required>
          @foreach ($suppliers as $supplier)
            <option value="{{ $supplier->id }}" {{ (int) old('supplier_id', $purchase->supplier_id) === $supplier->id ? 'selected' : '' }}>
              {{ $supplier->name }}
            </option>
          @endforeach
        </select>
        @error('supplier_id') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="purchase_date">Purchase date</label>
          <input type="date" id="purchase_date" name="purchase_date"
                 value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required>
          @error('purchase_date') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label for="items_count">Item count</label>
          <input type="number" min="0" id="items_count" name="items_count" value="{{ old('items_count', $purchase->items_count) }}" required>
          @error('items_count') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
          <option value="pending" {{ old('status', $purchase->status) === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="received" {{ old('status', $purchase->status) === 'received' ? 'selected' : '' }}>Received</option>
        </select>
      </div>

      <div class="form-group">
        <label for="total_cost">Total cost (₱)</label>
        <input type="number" step="0.01" min="0" id="total_cost" name="total_cost" value="{{ old('total_cost', $purchase->total_cost) }}" required>
        @error('total_cost') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-actions">
        <a class="btn btn-ghost" href="{{ route('purchases.index') }}">Cancel</a>
        <button type="submit" class="btn">Update purchase</button>
      </div>
    </form>
  </div>

@endsection

@extends('layouts.app')

@section('title', 'Purchases — BiteSync')
@section('tagline', 'All purchase orders')

@section('content')

  <div class="panel">
    <div class="head">
      <h2>All purchases</h2>
      <div class="head-actions">
        <a class="btn" href="{{ route('purchases.create') }}">+ New purchase</a>
      </div>
    </div>
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th>Purchase #</th>
            <th>Supplier</th>
            <th>Date</th>
            <th class="num">Items</th>
            <th>Status</th>
            <th class="num">Total cost</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($purchases as $purchase)
            <tr>
              <td>{{ $purchase->purchase_no }}</td>
              <td>{{ $purchase->supplier->name ?? '—' }}</td>
              <td>{{ $purchase->purchase_date->format('M j, Y') }}</td>
              <td class="num">{{ $purchase->items_count }}</td>
              <td><span class="badge status-{{ $purchase->status }}">{{ ucfirst($purchase->status) }}</span></td>
              <td class="num amount">₱{{ number_format($purchase->total_cost, 2) }}</td>
              <td class="row-actions">
                <a href="{{ route('purchases.edit', $purchase) }}">Edit</a>
                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" onsubmit="return confirm('Delete this purchase?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="link-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="empty">No purchases recorded yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="pagination">
    {{ $purchases->links() }}
  </div>

@endsection

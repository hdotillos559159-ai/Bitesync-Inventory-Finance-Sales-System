@extends('layouts.app')

@section('title', 'Sales — BiteSync')
@section('tagline', 'All sales records')

@section('content')

  <div class="panel">
    <div class="head">
      <h2>All sales</h2>
      <div class="head-actions">
        <a class="btn" href="{{ route('sales.create') }}">+ New sale</a>
      </div>
    </div>
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th>Sale number</th>
            <th>Date &amp; time</th>
            <th>Payment</th>
            <th>Discount</th>
            <th class="num">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($sales as $sale)
            <tr>
              <td>{{ $sale->sale_number }}</td>
              <td>{{ $sale->sale_date->format('M j, Y g:i A') }}</td>
              <td><span class="badge {{ $sale->payment_method }}">{{ $sale->payment_method === 'cash' ? 'Cash' : 'GCash' }}</span></td>
              <td>{{ $sale->discount ? number_format($sale->discount, 2) : '—' }}</td>
              <td class="num amount">₱{{ number_format($sale->total, 2) }}</td>
              <td class="row-actions">
                <a href="{{ route('sales.edit', $sale) }}">Edit</a>
                <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Delete this sale?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="link-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="empty">No sales recorded yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="pagination">
    {{ $sales->links() }}
  </div>

@endsection

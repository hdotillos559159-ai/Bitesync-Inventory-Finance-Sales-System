@extends('layouts.app')

@section('title', 'Expenses — BiteSync')
@section('tagline', 'All expense records')

@section('content')

  <div class="panel">
    <div class="head">
      <h2>All expenses</h2>
      <div class="head-actions">
        <a class="btn" href="{{ route('expenses.create') }}">+ New expense</a>
      </div>
    </div>
    <div class="table-scroll">
      <table>
        <thead>
          <tr>a
            <th>Expense ID</th>
            <th>Category</th>
            <th>Date</th>
            <th>Recorded by</th>
            <th>Linked purchase</th>
            <th class="num">Amount</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($expenses as $expense)
            <tr>
              <td>{{ $expense->expense_no }}</td>
              <td><span class="badge cat">{{ $expense->category }}</span></td>
              <td>{{ $expense->expense_date->format('M j, Y') }}</td>
              <td>{{ $expense->recorded_by }}</td>
              <td class="linked">{{ $expense->purchase->purchase_no ?? '—' }}</td>
              <td class="num amount">₱{{ number_format($expense->amount, 2) }}</td>
              <td class="row-actions">
                <a href="{{ route('expenses.edit', $expense) }}">Edit</a>
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this expense?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="link-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="empty">No expenses recorded yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="pagination">
    {{ $expenses->links() }}
  </div>

@endsection

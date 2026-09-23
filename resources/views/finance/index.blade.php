@extends('layouts.app')

@section('tagline', 'Sales, expenses & purchases')

@section('content')

  <div class="period-row">
    <div class="period">Showing records for <strong>{{ $periodLabel }}</strong></div>
  </div>

  <nav class="tabs">
    <button class="active" data-tab="sales">Sales</button>
    <button data-tab="expenses">Expenses</button>
    <button data-tab="purchases">Purchases</button>
  </nav>

  <!-- SALES TAB -->
  <section id="tab-sales">
    <div class="stats">
      <div class="stat">
        <div class="label">Total sales</div>
        <div class="value" id="salesTotal">—</div>
        <div class="sub" id="salesTxnCount">—</div>
      </div>
      <div class="stat">
        <div class="label">Cash collected</div>
        <div class="value pos" id="salesCash">—</div>
        <div class="sub">Goes into remittance</div>
      </div>
      <div class="stat">
        <div class="label">Non-cash</div>
        <div class="value" id="salesNonCash">—</div>
        <div class="sub">GCash &amp; other</div>
      </div>
      <div class="stat">
        <div class="label">Avg. order</div>
        <div class="value" id="salesAvg">—</div>
        <div class="sub">Per transaction</div>
      </div>
    </div>

    <div class="trend">
      <div class="head">
        <h2>Daily sales, last 7 days</h2>
        <div class="note">Cash + non-cash combined</div>
      </div>
      <div class="bars" id="salesBars"></div>
    </div>

    <div class="panel">
      <div class="head">
        <h2>Recent sales</h2>
        <div class="head-actions">
          <input class="search" id="salesSearch" placeholder="Filter by ref, payment, or type…" />
          <a class="btn" href="{{ route('sales.create') }}">+ New sale</a>
        </div>
      </div>
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>POS ref</th>
              <th>Date &amp; time</th>
              <th>Order type</th>
              <th>Payment</th>
              <th>Discount</th>
              <th class="num">Total</th>
            </tr>
          </thead>
          <tbody id="salesBody"></tbody>
        </table>
      </div>
      <div class="empty" id="salesEmpty" style="display:none;">No sales match that filter.</div>
    </div>
  </section>

  <!-- EXPENSES TAB -->
  <section id="tab-expenses" style="display:none;">
    <div class="stats">
      <div class="stat">
        <div class="label">Total expenses</div>
        <div class="value neg" id="expTotal">—</div>
        <div class="sub" id="expCount">—</div>
      </div>
      <div class="stat">
        <div class="label">From purchases</div>
        <div class="value" id="expFromPurchase">—</div>
        <div class="sub">Linked to a purchase order</div>
      </div>
      <div class="stat">
        <div class="label">Other expenses</div>
        <div class="value" id="expOther">—</div>
        <div class="sub">Utilities, repairs, etc.</div>
      </div>
      <div class="stat">
        <div class="label">Net (sales − expenses)</div>
        <div class="value pos" id="expNet">—</div>
        <div class="sub">This period</div>
      </div>
    </div>

    <div class="trend">
      <div class="head">
        <h2>Spend by category</h2>
        <div class="note">Share of total expenses</div>
      </div>
      <div class="bars" id="expBars"></div>
    </div>

    <div class="panel">
      <div class="head">
        <h2>Recent expenses</h2>
        <div class="head-actions">
          <input class="search" id="expSearch" placeholder="Filter by category or recorded by…" />
          <a class="btn" href="{{ route('expenses.create') }}">+ New expense</a>
        </div>
      </div>
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>Expense ID</th>
              <th>Category</th>
              <th>Date</th>
              <th>Recorded by</th>
              <th>Linked purchase</th>
              <th class="num">Amount</th>
            </tr>
          </thead>
          <tbody id="expBody"></tbody>
        </table>
      </div>
      <div class="empty" id="expEmpty" style="display:none;">No expenses match that filter.</div>
    </div>
  </section>

  <!-- PURCHASES TAB -->
  <section id="tab-purchases" style="display:none;">
    <div class="stats">
      <div class="stat">
        <div class="label">Total purchases</div>
        <div class="value" id="purchTotal">—</div>
        <div class="sub" id="purchCount">—</div>
      </div>
      <div class="stat">
        <div class="label">Received</div>
        <div class="value pos" id="purchReceived">—</div>
        <div class="sub">Stocked into inventory</div>
      </div>
      <div class="stat">
        <div class="label">Pending</div>
        <div class="value neg" id="purchPending">—</div>
        <div class="sub">Awaiting delivery</div>
      </div>
      <div class="stat">
        <div class="label">Suppliers</div>
        <div class="value" id="purchSuppliers">—</div>
        <div class="sub">Active this period</div>
      </div>
    </div>

    <div class="trend">
      <div class="head">
        <h2>Spend by supplier</h2>
        <div class="note">Share of total purchase cost</div>
      </div>
      <div class="bars" id="purchBars"></div>
    </div>

    <div class="panel">
      <div class="head">
        <h2>Recent purchases</h2>
        <div class="head-actions">
          <input class="search" id="purchSearch" placeholder="Filter by supplier or status…" />
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
            </tr>
          </thead>
          <tbody id="purchBody"></tbody>
        </table>
      </div>
      <div class="empty" id="purchEmpty" style="display:none;">No purchases match that filter.</div>
    </div>
  </section>

@endsection

@section('scripts')
  <script>
    // Data computed server-side by FinanceController, consumed by finance.js
    window.FINANCE_DATA = {
      sales: @json($sales),
      dailyTotals: @json($dailyTotals),
      purchases: @json($purchases),
      supplierSpend: @json($supplierSpend),
      expenses: @json($expenses),
      categorySpend: @json($categorySpend),
    };
  </script>
@endsection

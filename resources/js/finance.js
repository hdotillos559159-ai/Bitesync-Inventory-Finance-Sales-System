const data = window.FINANCE_DATA;

if (data) {
    const money = (value) => `₱${Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    const date = (value, options) => new Intl.DateTimeFormat('en-PH', options).format(new Date(value));
    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
    const sales = data.sales || [];
    const expenses = data.expenses || [];
    const purchases = data.purchases || [];

    const renderBars = (elementId, entries, formatter = money) => {
        const element = document.getElementById(elementId);
        if (!element) return;
        const values = Object.entries(entries || {});
        const max = Math.max(...values.map(([, value]) => Number(value)), 1);
        element.innerHTML = values.length ? values.map(([label, value]) => `
            <div class="bar-group" title="${escapeHtml(label)}: ${formatter(value)}">
                <div class="bar" style="height:${Math.max(Number(value) / max * 100, 3)}%"></div>
                <div class="bar-label">${escapeHtml(label).slice(-5)}</div>
            </div>`).join('') : '<div class="empty">No records for this period.</div>';
    };

    const renderSales = (query = '') => {
        const body = document.getElementById('salesBody');
        const empty = document.getElementById('salesEmpty');
        const filtered = sales.filter((sale) => `${sale.pos_ref} ${sale.payment_method} ${sale.order_type}`.toLowerCase().includes(query.toLowerCase()));
        body.innerHTML = filtered.map((sale) => `
            <tr>
                <td><strong>${escapeHtml(sale.pos_ref)}</strong></td>
                <td>${date(sale.sale_date, { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' })}</td>
                <td><span class="badge ${escapeHtml(sale.order_type)}">${sale.order_type === 'dine' ? 'Dine-in' : 'Takeaway'}</span></td>
                <td><span class="badge ${escapeHtml(sale.payment_method)}">${sale.payment_method === 'cash' ? 'Cash' : 'GCash'}</span></td>
                <td>${escapeHtml(sale.discount || '—')}</td>
                <td class="num amount">${money(sale.total)}</td>
            </tr>`).join('');
        empty.style.display = filtered.length ? 'none' : 'block';
    };

    const renderExpenses = (query = '') => {
        const body = document.getElementById('expBody');
        const empty = document.getElementById('expEmpty');
        const filtered = expenses.filter((expense) => `${expense.category} ${expense.recorded_by}`.toLowerCase().includes(query.toLowerCase()));
        body.innerHTML = filtered.map((expense) => `
            <tr><td><strong>${escapeHtml(expense.expense_no)}</strong></td><td>${escapeHtml(expense.category)}</td><td>${date(expense.expense_date, { month: 'short', day: 'numeric', year: 'numeric' })}</td><td>${escapeHtml(expense.recorded_by)}</td><td>${expense.purchase_id ? `PO #${escapeHtml(expense.purchase_id)}` : '—'}</td><td class="num amount">${money(expense.amount)}</td></tr>`).join('');
        empty.style.display = filtered.length ? 'none' : 'block';
    };

    const renderPurchases = (query = '') => {
        const body = document.getElementById('purchBody');
        const empty = document.getElementById('purchEmpty');
        const filtered = purchases.filter((purchase) => `${purchase.purchase_no} ${purchase.supplier?.name || ''} ${purchase.status}`.toLowerCase().includes(query.toLowerCase()));
        body.innerHTML = filtered.map((purchase) => `
            <tr><td><strong>${escapeHtml(purchase.purchase_no)}</strong></td><td>${escapeHtml(purchase.supplier?.name || 'Unknown')}</td><td>${date(purchase.purchase_date, { month: 'short', day: 'numeric', year: 'numeric' })}</td><td class="num">${purchase.items_count}</td><td><span class="badge ${purchase.status === 'pending' ? 'take' : ''}">${escapeHtml(purchase.status)}</span></td><td class="num amount">${money(purchase.total_cost)}</td></tr>`).join('');
        empty.style.display = filtered.length ? 'none' : 'block';
    };

    const salesTotal = sales.reduce((sum, sale) => sum + Number(sale.total || 0), 0);
    const cash = sales.filter((sale) => sale.payment_method === 'cash').reduce((sum, sale) => sum + Number(sale.total || 0), 0);
    const expensesTotal = expenses.reduce((sum, expense) => sum + Number(expense.amount || 0), 0);
    const purchasesTotal = purchases.reduce((sum, purchase) => sum + Number(purchase.total_cost || 0), 0);
    document.getElementById('salesTotal').textContent = money(salesTotal);
    document.getElementById('salesTxnCount').textContent = `${sales.length} transaction${sales.length === 1 ? '' : 's'}`;
    document.getElementById('salesCash').textContent = money(cash);
    document.getElementById('salesNonCash').textContent = money(salesTotal - cash);
    document.getElementById('salesAvg').textContent = money(sales.length ? salesTotal / sales.length : 0);
    document.getElementById('expTotal').textContent = money(expensesTotal);
    document.getElementById('expCount').textContent = `${expenses.length} expense${expenses.length === 1 ? '' : 's'}`;
    document.getElementById('expFromPurchase').textContent = money(expenses.filter((expense) => expense.purchase_id).reduce((sum, expense) => sum + Number(expense.amount || 0), 0));
    document.getElementById('expOther').textContent = money(expenses.filter((expense) => !expense.purchase_id).reduce((sum, expense) => sum + Number(expense.amount || 0), 0));
    document.getElementById('expNet').textContent = money(salesTotal - expensesTotal);
    document.getElementById('purchTotal').textContent = money(purchasesTotal);
    document.getElementById('purchCount').textContent = `${purchases.length} purchase${purchases.length === 1 ? '' : 's'}`;
    document.getElementById('purchReceived').textContent = money(purchases.filter((purchase) => purchase.status === 'received').reduce((sum, purchase) => sum + Number(purchase.total_cost || 0), 0));
    document.getElementById('purchPending').textContent = money(purchases.filter((purchase) => purchase.status === 'pending').reduce((sum, purchase) => sum + Number(purchase.total_cost || 0), 0));
    document.getElementById('purchSuppliers').textContent = new Set(purchases.map((purchase) => purchase.supplier_id)).size;

    renderSales();
    renderExpenses();
    renderPurchases();
    renderBars('salesBars', data.dailyTotals);
    renderBars('expBars', data.categorySpend);
    renderBars('purchBars', data.supplierSpend);

    document.querySelectorAll('[data-tab]').forEach((button) => button.addEventListener('click', () => {
        document.querySelectorAll('[data-tab]').forEach((item) => item.classList.remove('active'));
        document.querySelectorAll('[id^="tab-"]').forEach((section) => { section.style.display = 'none'; });
        button.classList.add('active');
        document.getElementById(`tab-${button.dataset.tab}`).style.display = 'block';
    }));
    document.getElementById('salesSearch').addEventListener('input', (event) => renderSales(event.target.value));
    document.getElementById('expSearch').addEventListener('input', (event) => renderExpenses(event.target.value));
    document.getElementById('purchSearch').addEventListener('input', (event) => renderPurchases(event.target.value));
}

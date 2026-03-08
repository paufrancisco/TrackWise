@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div style="margin-bottom: 28px;">
    <h1 style="font-size: 22px; font-weight: 600; letter-spacing: -0.5px; margin: 0 0 4px;">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
        {{ explode(' ', auth()->user()->name)[0] }}
    </h1>
    <p style="color: var(--muted); font-size: 13px; margin: 0;">
        {{ now()->format('l, F j, Y') }} &mdash; Here&rsquo;s your financial overview
    </p>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="stat-card stat-income">
        <div class="stat-label">Total Income</div>
        <div class="stat-value">&#8369;{{ number_format($totalIncome, 2) }}</div>
        <div class="stat-sub">This month</div>
    </div>
    <div class="stat-card stat-expense">
        <div class="stat-label">Total Expenses</div>
        <div class="stat-value">&#8369;{{ number_format($totalExpense, 2) }}</div>
        <div class="stat-sub">This month</div>
    </div>
    <div class="stat-card stat-balance">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">&#8369;{{ number_format($balance, 2) }}</div>
        <div class="stat-sub">Income minus expenses</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 16px; margin-bottom: 24px;">

    <div class="card">
        <div class="card-header">
            <div style="display: flex; gap: 6px;">
                <button id="tab-expense"
                    style="padding: 4px 10px; font-size: 11px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; font-family: inherit; background: var(--accent); color: #0f1117;"
                    onclick="switchPieTab('expense')">
                    Expenses
                </button>
                <button id="tab-income"
                    style="padding: 4px 10px; font-size: 11px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; font-family: inherit; background: transparent; color: var(--muted);"
                    onclick="switchPieTab('income')">
                    Income
                </button>
            </div>
        </div>
        <div class="card-pad" style="display: flex; align-items: center; justify-content: center; min-height: 240px;">
            <canvas id="expensesPieChart" style="max-height: 220px;"></canvas>
            <canvas id="incomePieChart" style="max-height: 220px; display: none;"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Monthly Overview</span>
            <span style="font-size: 11px; color: var(--muted);">Last 6 months</span>
        </div>
        <div class="card-pad">
            <canvas id="barChart" style="max-height: 220px;"></canvas>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Recent Transactions</span>
        <a href="{{ route('transactions.index') }}"
           style="font-size: 12px; color: var(--accent); text-decoration: none;">
            View all &rarr;
        </a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Category</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentTransactions as $transaction)
                <tr>
                    <td style="color: var(--muted); font-family: 'DM Mono', monospace; font-size: 12px;">
                        {{ $transaction->transaction_date->format('M d, Y') }}
                    </td>
                    <td style="font-weight: 500;">{{ $transaction->title }}</td>
                    <td>
                        <span class="badge {{ $transaction->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                            {{ $transaction->category }}
                        </span>
                    </td>
                    <td class="text-right">
                        <span class="amount {{ $transaction->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}&#8369;{{ number_format($transaction->amount, 2) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <div class="empty-icon">&#128184;</div>
                            No transactions yet. Add your first one!
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
    var COLORS = ['#6ee7b7', '#f87171', '#60a5fa', '#fbbf24', '#a78bfa', '#fb7185', '#34d399']
    var expenseData = {!! json_encode($expenseByCategory) !!}
    var incomeData = {!! json_encode($incomeByCategory) !!}
    var monthlyData = {!! json_encode($monthlyData) !!}

    var PIE_OPTIONS = {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16, font: { size: 11, family: 'DM Sans' } } }
        },
        cutout: '65%'
    }

    new Chart(document.getElementById('expensesPieChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(expenseData),
            datasets: [{ data: Object.values(expenseData), backgroundColor: COLORS, borderColor: '#16181f', borderWidth: 3 }]
        },
        options: PIE_OPTIONS
    })

    new Chart(document.getElementById('incomePieChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(incomeData),
            datasets: [{ data: Object.values(incomeData), backgroundColor: COLORS, borderColor: '#16181f', borderWidth: 3 }]
        },
        options: PIE_OPTIONS
    })

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.map(function(m) { return m.month }),
            datasets: [
                { label: 'Income', data: monthlyData.map(function(m) { return m.income }), backgroundColor: 'rgba(110,231,183,0.7)', borderRadius: 4 },
                { label: 'Expenses', data: monthlyData.map(function(m) { return m.expense }), backgroundColor: 'rgba(248,113,113,0.7)', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { font: { size: 11, family: 'DM Sans' } } } },
            scales: {
                x: { grid: { color: '#2a2d3a' }, ticks: { font: { size: 11 } } },
                y: { grid: { color: '#2a2d3a' }, ticks: { font: { size: 11 } } }
            }
        }
    })

    function switchPieTab(tab) {
        document.getElementById('expensesPieChart').style.display = tab === 'expense' ? 'block' : 'none'
        document.getElementById('incomePieChart').style.display = tab === 'income' ? 'block' : 'none'
        document.getElementById('tab-expense').style.background = tab === 'expense' ? 'var(--accent)' : 'transparent'
        document.getElementById('tab-expense').style.color = tab === 'expense' ? '#0f1117' : 'var(--muted)'
        document.getElementById('tab-income').style.background = tab === 'income' ? 'var(--accent)' : 'transparent'
        document.getElementById('tab-income').style.color = tab === 'income' ? '#0f1117' : 'var(--muted)'
    }
</script>
@endpush
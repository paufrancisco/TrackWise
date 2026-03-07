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

{{-- Stat Cards --}}
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
        <div class="stat-label">Net Balance</div>
        <div class="stat-value">&#8369;{{ number_format($balance, 2) }}</div>
        <div class="stat-sub">Income minus expenses</div>
    </div>
</div>

{{-- Charts --}}
<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 16px; margin-bottom: 24px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Expenses by Category</span>
        </div>
        <div class="card-pad" style="display: flex; align-items: center; justify-content: center;">
            <canvas id="pieChart" style="max-height: 220px;"></canvas>
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

{{-- Recent Transactions --}}
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

<script>
  Chart.defaults.color = '#6b7280'
  Chart.defaults.borderColor = '#2a2d3a'

  const pieCtx = document.getElementById('pieChart')
  const expenseData = @json($expenseByCategory)

  new Chart(pieCtx, {
    type: 'doughnut',
    data: {
      labels: Object.keys(expenseData),
      datasets: [{
        data: Object.values(expenseData),
        backgroundColor: ['#6ee7b7','#f87171','#60a5fa','#fbbf24','#a78bfa','#fb7185','#34d399'],
        borderColor: '#16181f',
        borderWidth: 3,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom', labels: { padding: 16, font: { size: 11, family: 'DM Sans' } } }
      },
      cutout: '65%',
    }
  })

  const barCtx = document.getElementById('barChart')
  const monthlyData = @json($monthlyData)

  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: monthlyData.map(m => m.month),
      datasets: [
        { label: 'Income', data: monthlyData.map(m => m.income), backgroundColor: 'rgba(110,231,183,0.7)', borderRadius: 4 },
        { label: 'Expenses', data: monthlyData.map(m => m.expense), backgroundColor: 'rgba(248,113,113,0.7)', borderRadius: 4 }
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
</script>

@endsection
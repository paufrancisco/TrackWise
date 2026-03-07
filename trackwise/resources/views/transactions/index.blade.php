@extends('layouts.app')
@section('title', 'All Transactions')

@section('content')

<div class="filter-bar">
    <form method="GET" action="{{ route('transactions.index') }}" class="filter-form">
        <input
            name="search"
            type="text"
            placeholder="Search transactions..."
            value="{{ request('search') }}"
            class="form-input filter-search"
        />

        <select name="type" id="typeFilter" class="form-select filter-type">
            <option value="">All Types</option>
            <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
        </select>

        <select name="category" id="categoryFilter" class="form-select filter-category">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->name }}"
                    data-type="{{ $category->type }}"
                    {{ request('category') === $category->name ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <input name="date_from" type="date" value="{{ request('date_from') }}" class="form-input filter-date" />
        <input name="date_to" type="date" value="{{ request('date_to') }}" class="form-input filter-date" />

        <button type="submit" class="btn btn-primary">Filter</button>

        @if (request()->anyFilled(['search', 'type', 'category', 'date_from', 'date_to']))
            <a href="{{ route('transactions.index') }}" class="filter-clear">&times; Clear</a>
        @endif
    </form>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Type</th>
                <th>Category</th>
                <th class="text-right">Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td class="transaction-date">
                        {{ $transaction->transaction_date->format('M d, Y') }}
                    </td>
                    <td>
                        <div class="transaction-title">{{ $transaction->title }}</div>
                        @if ($transaction->description)
                            <div class="transaction-description">{{ $transaction->description }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $transaction->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </td>
                    <td class="transaction-category">{{ $transaction->category }}</td>
                    <td class="text-right">
                        <span class="amount {{ $transaction->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}&#8369;{{ number_format($transaction->amount, 2) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-ghost btn-sm">Edit</a>
                            <form
                                method="POST"
                                action="{{ route('transactions.destroy', $transaction) }}"
                                class="delete-transaction-form"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon">&#128202;</div>
                            No transactions found.
                            <a href="{{ route('transactions.create') }}" class="empty-state-link">
                                Add your first transaction &rarr;
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $transactions->links() }}
</div>

<script>
  const typeFilter = document.getElementById('typeFilter')
  const categoryFilter = document.getElementById('categoryFilter')

  /** Filter category options based on the selected transaction type */
  const filterCategories = () => {
    const type = typeFilter.value

    Array.from(categoryFilter.options).forEach(opt => {
      if (!opt.value) return

      const match = !type || opt.dataset.type === type
      opt.hidden = !match
      opt.disabled = !match
    })

    const selected = categoryFilter.options[categoryFilter.selectedIndex]
    if (selected && selected.hidden) categoryFilter.value = ''
  }

  /** Confirm and submit delete form on user approval */
  const handleDeleteForms = () => {
    document.querySelectorAll('.delete-transaction-form').forEach(form => {
      form.addEventListener('submit', e => {
        if (!confirm('Delete this transaction?')) e.preventDefault()
      })
    })
  }

  typeFilter.addEventListener('change', filterCategories)

  filterCategories()
  handleDeleteForms()
</script>

@endsection
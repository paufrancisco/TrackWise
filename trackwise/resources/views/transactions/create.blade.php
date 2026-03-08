@extends('layouts.app')
@section('title', 'Add Transaction')

@section('content')

<div style="max-width: 560px;">

    <div class="card">
        <div class="card-header">
            <span class="card-title">New Transaction</span>
        </div>
        <div class="card-pad">
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf

                <div style="margin-bottom: 24px;">
                    <label class="field-label">Transaction Type</label>
                    <div class="type-selector">
                        <div class="type-option">
                            <input type="radio" name="type" value="income" id="type-income"
                                   {{ old('type', 'expense') === 'income' ? 'checked' : '' }}>
                            <label class="type-label income-label" for="type-income">
                                &#8593; Income
                            </label>
                        </div>
                        <div class="type-option">
                            <input type="radio" name="type" value="expense" id="type-expense"
                                   {{ old('type', 'expense') === 'expense' ? 'checked' : '' }}>
                            <label class="type-label expense-label" for="type-expense">
                                &#8595; Expense
                            </label>
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 18px;">

                    <div>
                        <label class="field-label" for="title">Title</label>
                        <input id="title" name="title" type="text"
                               value="{{ old('title') }}"
                               placeholder="e.g. Monthly Salary"
                               class="form-input {{ $errors->has('title') ? 'has-error' : '' }}" />
                        @error('title')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="field-label" for="amount">Amount</label>
                            <input id="amount" name="amount" type="number" min="0" step="0.01"
                                   value="{{ old('amount') }}" placeholder="0.00"
                                   class="form-input {{ $errors->has('amount') ? 'has-error' : '' }}" />
                            @error('amount')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="field-label" for="transaction_date">Date</label>
                            <input id="transaction_date" name="transaction_date" type="date"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}"
                                   class="form-input" />
                        </div>
                    </div>

                    <div>
                        <label class="field-label" for="categorySelect">Category</label>
                        <select id="categorySelect" name="category"
                                class="form-select {{ $errors->has('category') ? 'has-error' : '' }}">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}"
                                        data-type="{{ $category->type }}"
                                        {{ old('category') === $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="description">Description <span style="opacity:0.5; font-size:10px;">(optional)</span></label>
                        <textarea id="description" name="description" rows="3"
                                  placeholder="Add a note..."
                                  class="form-textarea">{{ old('description') }}</textarea>
                    </div>

                </div>

                <div style="display: flex; gap: 12px; margin-top: 28px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; padding: 12px;">
                        Save Transaction
                    </button>
                    <a href="{{ route('transactions.index') }}"
                       class="btn btn-ghost" style="justify-content: center; padding: 12px 20px;">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
    const filterCategories = () => {
        const checked = document.querySelector('input[name="type"]:checked')
        const type = checked ? checked.value : 'expense'
        const select = document.getElementById('categorySelect')

        Array.from(select.options).forEach(opt => {
            if (!opt.value) return
            opt.hidden = opt.dataset.type !== type
        })

        if (select.selectedOptions[0]?.hidden) select.value = ''
    }

    document.querySelectorAll('input[name="type"]').forEach(r => {
        r.addEventListener('change', filterCategories)
    })

    filterCategories()
</script>

@endsection
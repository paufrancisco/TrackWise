<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transactions Export</title>
    <style>

        * { margin: 0; padding: 0; box-sizing: border-box; }

        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path("fonts/DejaVuSans.ttf") }}');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e2029;
            background: #fff;
            padding: 32px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 2px solid #0f1117;
        }

        .logo { font-size: 22px; font-weight: 700; color: #0f1117; letter-spacing: -0.5px; }
        .logo em { font-style: normal; color: #059669; }

        .header-meta { text-align: right; color: #6b7280; font-size: 10px; line-height: 1.8; }
        .header-meta strong { color: #0f1117; }

        /* Summary cards */
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 24px;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .summary-row { display: table-row; }

        .summary-cell {
            display: table-cell;
            width: 33.33%;
            padding: 14px 18px;
            border-radius: 8px;
            border-top: 3px solid #ccc;
        }

        .summary-income { background: #f0fdf4; border-top-color: #059669; }
        .summary-expense { background: #fef2f2; border-top-color: #dc2626; }
        .summary-balance { background: #eff6ff; border-top-color: #2563eb; }

        .summary-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .summary-income .summary-value { color: #059669; }
        .summary-expense .summary-value { color: #dc2626; }
        .summary-balance .summary-value { color: #2563eb; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #0f1117;
            color: #fff;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        thead th.text-right { text-align: right; }

        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody tr:nth-child(odd) { background: #ffffff; }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10.5px;
            color: #374151;
        }

        tbody td.text-right { text-align: right; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-income { background: #d1fae5; color: #065f46; }
        .badge-expense { background: #fee2e2; color: #991b1b; }

        .amount-income { color: #059669; font-weight: 600; }
        .amount-expense { color: #dc2626; font-weight: 600; }

        /* Footer */
        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            color: #9ca3af;
            font-size: 9px;
        }

        .total-row td {
            font-weight: 700;
            font-size: 11px;
            background: #f3f4f6 !important;
            border-top: 2px solid #0f1117;
            color: #0f1117;
        }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="logo">Track<em>Wise</em></div>
            <div style="font-size: 10px; color: #6b7280; margin-top: 4px;">
                Transaction Report
            </div>
        </div>
        <div class="header-meta">
            <strong>{{ $user->name }}</strong><br>
            {{ $user->email }}<br>
            Generated: {{ now()->format('F j, Y \a\t g:i A') }}<br>
            Total Records: {{ $transactions->count() }}
        </div>
    </div>

    {{-- Summary --}}
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 33%; padding: 14px 18px; background: #f0fdf4; border-top: 3px solid #059669; border-radius: 6px;">
                <div style="font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6b7280; margin-bottom: 5px;">Total Income</div>
                <div style="font-size: 17px; font-weight: 700; color: #059669;">PHP {{ number_format($totalIncome, 2) }}</div>
            </td>
            <td style="width: 4px;"></td>
            <td style="width: 33%; padding: 14px 18px; background: #fef2f2; border-top: 3px solid #dc2626; border-radius: 6px;">
                <div style="font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6b7280; margin-bottom: 5px;">Total Expenses</div>
                <div style="font-size: 17px; font-weight: 700; color: #dc2626;">PHP {{ number_format($totalExpense, 2) }}</div>
            </td>
            <td style="width: 4px;"></td>
            <td style="width: 33%; padding: 14px 18px; background: #eff6ff; border-top: 3px solid #2563eb; border-radius: 6px;">
                <div style="font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6b7280; margin-bottom: 5px;">Net Balance</div>
                <div style="font-size: 17px; font-weight: 700; color: #2563eb;">PHP {{ number_format($balance, 2) }}</div>            
            </td>
        </tr>
    </table>

    {{-- Transactions Table --}}
    <table>
        <thead>
            <tr>
                <th style="width: 90px;">Date</th>
                <th>Title</th>
                <th style="width: 70px;">Type</th>
                <th style="width: 110px;">Category</th>
                <th style="width: 110px; text-align: right;">Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td style="color: #6b7280; font-size: 10px;">
                        {{ $transaction->transaction_date->format('M d, Y') }}
                    </td>
                    <td style="font-weight: 500;">{{ $transaction->title }}</td>
                    <td>
                        <span class="badge {{ $transaction->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                            {{ $transaction->type }}
                        </span>
                    </td>
                    <td style="color: #6b7280;">{{ $transaction->category }}</td>
                    <td class="text-right {{ $transaction->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}PHP {{ number_format($transaction->amount, 2) }}
                    </td>
                    <td style="color: #9ca3af; font-size: 10px;">{{ $transaction->description ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 32px; color: #9ca3af;">
                        No transactions found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>TrackWise &mdash; Personal Finance Tracker</span>
        <span>{{ now()->format('Y') }} &bull; {{ $user->name }}</span>
    </div>

</body>
</html>
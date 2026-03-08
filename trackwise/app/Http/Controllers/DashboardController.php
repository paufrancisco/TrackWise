<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user         = $request->user();
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        $transactions = $user->transactions()
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        $expenseByCategory = $transactions
            ->where('type', 'expense')
            ->groupBy('category')
            ->map(fn ($items) => $items->sum('amount'));

        $incomeByCategory = $transactions          // 👈 added
            ->where('type', 'income')
            ->groupBy('category')
            ->map(fn ($items) => $items->sum('amount'));

        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date    = Carbon::now()->subMonths($i);
            $monthTx = $user->transactions()
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->get();

            $monthlyData[] = [
                'month'   => $date->format('M Y'),
                'income'  => $monthTx->where('type', 'income')->sum('amount'),
                'expense' => $monthTx->where('type', 'expense')->sum('amount'),
            ];
        }

        $recentTransactions = $user->transactions()
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'expenseByCategory',
            'incomeByCategory',      
            'monthlyData',
            'recentTransactions'
        ));
    }
}
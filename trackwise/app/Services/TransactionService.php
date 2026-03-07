<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionService
{
    /**
     * Get filtered and paginated transactions for a user.
     *
     * @param  User    $user    the authenticated user
     * @param  Request $request the incoming HTTP request with filter params
     * @return LengthAwarePaginator
     */
    public function getFilteredTransactions(User $user, Request $request): LengthAwarePaginator
    {
        $query = $user->transactions()->orderBy('transaction_date', 'desc');

        if ($request->input('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->input('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->input('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->input('date_from'));
        }

        if ($request->input('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->input('date_to'));
        }

        if ($request->input('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        return $query->paginate(15)->withQueryString();
    }

    /**
     * Store a new transaction for a user.
     *
     * @param  User  $user the authenticated user
     * @param  array $data validated transaction data
     * @return Transaction
     */
    public function store(User $user, array $data): Transaction
    {
        return $user->transactions()->create($data);
    }

    /**
     * Update an existing transaction with new data.
     *
     * @param  Transaction $transaction the transaction to update
     * @param  array       $data        validated transaction data
     * @return bool
     */
    public function update(Transaction $transaction, array $data): bool
    {
        return $transaction->update($data);
    }

    /**
     * Delete a transaction from the database.
     *
     * @param  Transaction $transaction the transaction to delete
     * @return bool|null
     */
    public function delete(Transaction $transaction): bool|null
    {
        return $transaction->delete();
    }

    /**
     * Export all user transactions as a downloadable PDF.
     *
     * @param  User $user the authenticated user
     * @return Response
     */
    public function exportPdf(User $user): Response
    {
        $transactions = $user->transactions()
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        $pdf = Pdf::loadView('exports.transactions-pdf', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'user'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('transactions-' . now()->format('Y-m-d') . '.pdf');
    }


    /**
     * Export all user transactions as a downloadable Excel file.
     *
     * @param  User $user the authenticated user
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportExcel(User $user): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $transactions = $user->transactions()
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transactions');

        // ── Header row ──
        $headers = ['Date', 'Title', 'Category', 'Type', 'Amount'];
        foreach ($headers as $col => $heading) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . '1';
            $sheet->setCellValue($cell, $heading);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // ── Data rows ──
        foreach ($transactions as $i => $tx) {
            $row = $i + 2;
            $sheet->setCellValue("A{$row}", \Carbon\Carbon::parse($tx->transaction_date)->format('Y-m-d'));
            $sheet->setCellValue("B{$row}", $tx->title);
            $sheet->setCellValue("C{$row}", $tx->category ?? '—');
            $sheet->setCellValue("D{$row}", ucfirst($tx->type));
            $sheet->setCellValue("E{$row}", number_format($tx->amount, 2, '.', ''));
        }

        // ── Summary rows ──
        $summaryRow = $transactions->count() + 3;
        $sheet->setCellValue("D{$summaryRow}", 'Total Income:');
        $sheet->setCellValue("E{$summaryRow}", number_format($totalIncome, 2, '.', ''));
        $sheet->getStyle("D{$summaryRow}")->getFont()->setBold(true);

        $summaryRow++;
        $sheet->setCellValue("D{$summaryRow}", 'Total Expenses:');
        $sheet->setCellValue("E{$summaryRow}", number_format($totalExpense, 2, '.', ''));
        $sheet->getStyle("D{$summaryRow}")->getFont()->setBold(true);

        $summaryRow++;
        $sheet->setCellValue("D{$summaryRow}", 'Balance:');
        $sheet->setCellValue("E{$summaryRow}", number_format($balance, 2, '.', ''));
        $sheet->getStyle("D{$summaryRow}")->getFont()->setBold(true);

        // ── Auto-size columns ──
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'transactions-' . now()->format('Y-m-d') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
} 
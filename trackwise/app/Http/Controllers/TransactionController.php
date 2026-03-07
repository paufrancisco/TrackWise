<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /** @var TransactionService */
    private TransactionService $transactionService;

    /**
     * @param TransactionService $transactionService injected transaction service
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display a paginated list of transactions with optional filters.
     *
     * @param  Request $request the incoming HTTP request
     * @return View
     */
    public function index(Request $request): View
    {
        $transactions = $this->transactionService
            ->getFilteredTransactions($request->user(), $request);

        $categories = $request->user()->categories()->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    /**
     * Show the form to create a new transaction.
     *
     * @param  Request $request the incoming HTTP request
     * @return View
     */
    public function create(Request $request): View
    {
        $categories = $request->user()->categories()->get();

        return view('transactions.create', compact('categories'));
    }

    /**
     * Store a newly created transaction in the database.
     *
     * @param  StoreTransactionRequest $request the validated form request
     * @return RedirectResponse
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $this->transactionService->store($request->user(), $request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction added successfully!');
    }

    /**
     * Show the form to edit an existing transaction.
     *
     * @param  Request     $request     the incoming HTTP request
     * @param  Transaction $transaction the transaction to edit
     * @return View
     */
    public function edit(Request $request, Transaction $transaction): View
    {
        $this->authorize('update', $transaction);

        $categories = $request->user()->categories()->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update an existing transaction in the database.
     *
     * @param  UpdateTransactionRequest $request     the validated form request
     * @param  Transaction              $transaction the transaction to update
     * @return RedirectResponse
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $this->transactionService->update($transaction, $request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated!');
    }

    /**
     * Remove a transaction from the database.
     *
     * @param  Transaction $transaction the transaction to delete
     * @return RedirectResponse
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);

        $this->transactionService->delete($transaction);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted!');
    }

    /**
     * Export all transactions as a PDF file download.
     *
     * @param  Request $request the incoming HTTP request
     * @return Response
     */
    public function export(Request $request): Response
    {
        return $this->transactionService->exportPdf($request->user());
    }


    /**
     * Export all transactions as an Excel file download.
     *
     * @param  Request $request the incoming HTTP request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportExcel(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return $this->transactionService->exportExcel($request->user());
    }
}
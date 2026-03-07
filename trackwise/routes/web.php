<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Must be defined before resource() to avoid route collision
    Route::get('/transactions/export/csv',
        [TransactionController::class, 'export'])->name('transactions.export');

    Route::resource('transactions', TransactionController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/transactions/export/pdf', [TransactionController::class, 'export'])
    ->name('transactions.export.pdf');

    Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])
    ->name('transactions.export.excel');
});

require __DIR__.'/auth.php';
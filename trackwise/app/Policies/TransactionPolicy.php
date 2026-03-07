<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine if the user can update the transaction.
     *
     * @param  User        $user        the authenticated user
     * @param  Transaction $transaction the transaction being updated
     * @return bool
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }

    /**
     * Determine if the user can delete the transaction.
     *
     * @param  User        $user        the authenticated user
     * @param  Transaction $transaction the transaction being deleted
     * @return bool
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }
}
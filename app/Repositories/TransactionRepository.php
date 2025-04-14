<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function findByTransactionId(string $transactionId): Transaction
    {
        return Transaction::where('transaction_id', $transactionId)->firstOrFail();
    }
}

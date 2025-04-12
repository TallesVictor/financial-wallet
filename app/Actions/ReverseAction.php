<?php

namespace App\Actions;

use App\Models\Transaction;
use App\Services\TransactionService;

class ReverseAction
{

    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function execute(Transaction $transaction, array $data): Transaction
    {
        return $this->service->reverse($transaction, $data['description']);
    }
}

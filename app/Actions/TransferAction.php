<?php

namespace App\Actions;

use App\Services\TransactionService;

class TransferAction
{

    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function execute(array $data)
    {
        return $this->service->transfer(auth()->user(), $data['recipient_id'], $data['amount'], $data['description'] ?? '');
    }
}

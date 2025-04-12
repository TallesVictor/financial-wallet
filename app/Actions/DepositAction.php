<?php

namespace App\Actions;

use App\Services\TransactionService;

class DepositAction
{

    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function execute(array $data)
    {
        return $this->service->deposit(auth()->user(),  $data['amount'], $data['description']??'');
    }
}

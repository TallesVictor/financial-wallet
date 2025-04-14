<?php

namespace App\Actions;

use App\Services\TransactionService;
use App\Services\UserService;

class UsersListAction
{

    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function execute(array $data)
    {
        return $this->service->list($data);
    }
}

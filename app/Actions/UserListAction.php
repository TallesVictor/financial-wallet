<?php

namespace App\Actions;

use App\Services\UserService;

class UserListAction
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

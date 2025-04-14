<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function store(array $data): User
    {
        return User::create($data);
    }

    public function list(array $data): Collection
    {

       if($data['not_me']) {
            return User::where('id', '!=', auth()->user()->id)->get();
       }

       return User::all();

    }
}

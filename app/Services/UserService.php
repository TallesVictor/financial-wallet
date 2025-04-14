<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Create a new user.
     *
     * @param  array  $data
     * @return User
     */
    public function store(array $data): User
    {
        return User::create($data);
    }

    /**
     * List all users, or all users except the authenticated one.
     *
     * @param  array  $data
     * @return Collection
     */
    public function list(array $data): Collection
    {

       if(isset($data['not_me']) && $data['not_me']) {
            return User::where('id', '!=', auth()->user()->id)->get();
       }

       return User::all();

    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\UserListAction;
use App\Actions\UserStoreAction;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{


    public function index(UserIndexRequest $request, UserListAction $action)
    {
        $users = $action->execute($request->validated());
        return response()->json(UserResource::collection($users), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request, UserStoreAction $action)
    {
        $action->execute($request->validated());
        return response()->json(['message' => 'User created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(new UserResource($user), 200);
    }

    /**
     * Return the balance of the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBalance()
    {
        $user = auth()->user();
        return response()->json(['balance' => (float) $user->balance], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\UserListAction;
use App\Actions\UserStoreAction;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends Controller
{


    /**
     * Return a list of all users, or all users except the authenticated one
     * if the `exclude_me` parameter is set to true.
     *
     * @param \App\Http\Requests\User\UserIndexRequest $request
     * @param \App\Actions\UserListAction $action
     * @return \Illuminate\Http\Response
     */
    public function index(UserIndexRequest $request, UserListAction $action)
    {
        $users = $action->execute($request->validated());
        return response()->json(UserResource::collection($users), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\User\UserStoreRequest  $request
     * @param  \App\Actions\UserStoreAction  $action
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request, UserStoreAction $action)
    {
        $action->execute($request->validated());
        return response()->json(['message' => 'User created successfully'], 201);
    }

    /**
     * Get a single user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if($user->id !== auth()->user()->id) {
            throw new AccessDeniedHttpException('Forbidden');
        }
        return response()->json(new UserResource($user), 200);
    }

    /**
     * Get the balance of the currently authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBalance()
    {
        $user = auth()->user();

        return response()->json(['balance' => (float) $user->balance], 200);
    }
}

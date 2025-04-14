<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * Login a user and return a JSON response with the authentication token.
     *
     * @param AuthLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthLoginRequest $request)
    {
        $token = $this->authService->login($request->validated());

        return response()->json(['token' => $token]);
    }

    /**
     * Revoke the authentication token of the user and remove the session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {

        $this->authService->logout($request);

        return response()->json(['message' => 'Logout successful']);
    }
}

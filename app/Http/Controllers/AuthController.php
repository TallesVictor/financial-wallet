<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function login(AuthLoginRequest $request)
    {
        $token = $this->authService->login($request->validated());
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return response()->json(['message' => 'Logout successful']);
    }
}

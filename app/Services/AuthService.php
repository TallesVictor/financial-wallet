<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AuthService
{
    public function login(array $credentials): string
    {
        if (Auth::guard('web')->attempt($credentials)) {
            request()->session()->regenerate();

            $user = Auth::guard('web')->user();
            return $user->createToken('api_token')->plainTextToken;
        }

        throw new ConflictHttpException('Credentials do not match.');
    }

    public function logout(Request $request): void
    {

        //Revoke the token api
        if ($request->bearerToken()) {
            $request->user()->tokens->each(function ($userToken)  {
                $userToken->delete();
            });
        }
        // Logout the user of web
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AuthService
{

    /**
     * Authenticate a user with the provided credentials.
     *
     * Attempts to log in the user using the web guard. If successful, regenerates
     * the session to prevent session fixation and returns a new API token.
     * If the credentials do not match, a ConflictHttpException is thrown.
     *
     * @param array $credentials The user's credentials for authentication.
     * @return string The generated plain text API token for the authenticated user.
     * @throws ConflictHttpException If the authentication attempt fails.
     */

    public function login(array $credentials): string
    {
        if (Auth::guard('web')->attempt($credentials)) {
            request()->session()->regenerate();

            $user = Auth::guard('web')->user();
            return $user->createToken('api_token')->plainTextToken;
        }

        throw new ConflictHttpException('Credentials do not match.');
    }
    /**
     * Log out the authenticated user by revoking all API tokens 
     * and invalidating the current session.
     *
     * @param \Illuminate\Http\Request $request The current HTTP request instance,
     * which may include a bearer token for API authentication.
     *
     * This method will revoke all tokens associated with the user if an API 
     * token is present. Additionally, it will log out the user from the web 
     * guard, invalidate the session, and regenerate the session token to 
     * prevent session fixation attacks.
     */

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

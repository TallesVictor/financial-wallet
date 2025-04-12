<?php

namespace App\Resolvers;

use OwenIt\Auditing\Contracts\UserResolver as UserResolverContract;

class CustomUserResolver implements UserResolverContract
{
    public static function resolve()
    {
       return auth()->user();
    }
}

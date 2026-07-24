<?php

namespace Workbench\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Workbench\App\Models\User;

/**
 * The workbench is a local sandbox, not a demo of Filament's auth. Logging in as the seeded
 * user keeps `composer start` a single command with no login step.
 */
class AutoLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() && ($user = User::query()->first())) {
            Auth::login($user);
        }

        return $next($request);
    }
}

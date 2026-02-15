<?php

declare(strict_types=1);

namespace App\Http\Middleware\Guard;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureProfileIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('user') && ! $request->user('user')->is_profile_completed) {
            return to_route('user.authentication.complete-profile.edit');
        }

        if ($request->user('creator') && ! $request->user('creator')->is_profile_completed) {
            return to_route('creator.authentication.complete-profile.edit');
        }

        return $next($request);
    }
}

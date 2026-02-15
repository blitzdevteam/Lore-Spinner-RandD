<?php

declare(strict_types=1);

namespace App\Http\Middleware\Guard;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureProfileIsIncompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('user')?->is_profile_completed) {
            return to_route('user.dashboard.index');
        }

        if ($request->user('creator')?->is_profile_completed) {
            return to_route('creator.dashboard.index');
        }

        return $next($request);
    }
}

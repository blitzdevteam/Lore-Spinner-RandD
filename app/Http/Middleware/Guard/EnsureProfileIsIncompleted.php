<?php

namespace App\Http\Middleware\Guard;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsIncompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('user')?->is_profile_completed) {
            return to_route('user.dashboard.index');
        }

        if ($request->user('writer')?->is_profile_completed) {
            return to_route('writer.dashboard.index');
        }

        return $next($request);
    }
}

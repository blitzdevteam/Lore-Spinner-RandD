<?php

namespace App\Http\Middleware\Guard;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('user') && ! $request->user('user')->is_profile_completed) {
            return to_route('user.authentication.complete-profile.edit');
        }

        if ($request->user('writer') && ! $request->user('writer')->is_profile_completed) {
            return to_route('writer.authentication.complete-profile.edit');
        }

        return $next($request);
    }
}

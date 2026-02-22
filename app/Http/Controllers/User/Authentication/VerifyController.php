<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Authentication;

use App\Models\User;
use Closure;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Inertia\Response;

final class VerifyController implements HasMiddleware
{
    /**
     * Define the middleware for the controller.
     */
    public static function middleware()
    {
        return [
            function (Request $request, Closure $next) {
                /** @var User $user */
                $user = $request->user('user');

                if ($user->hasVerifiedEmail()) {
                    return to_route('user.dashboard.index');
                }

                return $next($request);
            },
        ];
    }

    /**
     * Show the verify page.
     */
    public function index(): Response
    {
        return inertia('User/Authentication/Verify');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(#[CurrentUser] User $user): RedirectResponse
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard.index');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'New verification link sent to your email address');
    }

    /**
     * Confirm the user's email address.
     */
    public function confirm(#[CurrentUser] User $user, EmailVerificationRequest $request): RedirectResponse
    {
        if (! $user->hasVerifiedEmail()) {
            $request->fulfill();
        }

        return to_route('user.authentication.complete-profile.edit')->with('success', 'Your email has been verified');
    }
}

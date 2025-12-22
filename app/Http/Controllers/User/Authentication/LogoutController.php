<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Authentication;

use App\Actions\Authentication\LogoutAuthenticatableGuardAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LogoutController
{
    /**
     * Destroy an authenticated session.
     */
    public function destroy(
        Request $request,
        LogoutAuthenticatableGuardAction $logoutAuthenticatableGuardAction
    ): RedirectResponse {
        $logoutAuthenticatableGuardAction->handle('user', $request);

        return to_route('user.authentication.login.create')->with('success', 'You have successfully logged out');
    }
}

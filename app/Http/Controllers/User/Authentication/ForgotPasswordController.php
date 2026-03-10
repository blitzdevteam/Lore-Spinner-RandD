<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Response;

final class ForgotPasswordController extends Controller
{
    public function create(): Response
    {
        return inertia('User/Authentication/ForgotPassword');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.exists' => 'We could not find an account with that email.',
        ]);

        $user = User::where('email', $request->string('email')->toString())->first();

        $user->forceFill([
            'password' => Hash::make($request->string('password')->toString()),
        ])->save();

        return to_route('user.authentication.login.create')
            ->with('success', 'Your password has been reset. You can now log in.');
    }
}

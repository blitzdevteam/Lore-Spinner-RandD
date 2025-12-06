<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * Alias custom middleware
         */
        $middleware->alias([
            'guard.profile-is-completed' => \App\Http\Middleware\Guard\EnsureProfileIsCompleted::class,
            'guard.profile-is-incompleted' => \App\Http\Middleware\Guard\EnsureProfileIsIncompleted::class,
        ]);

        /**
         * Append custom middleware to the "web" middleware group
         */
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        /**
         * Custom redirects for guests and authenticated users
         */
        $middleware->redirectGuestsTo(function (Request $request): string {
            if ($request->is('user/*')) {
                return route('user.authentication.login.create');
            }

            if ($request->is('writer/*')) {
                return route('writer.authentication.login.create');
            }

            return route('index');
        });

        /**
         * Custom redirects for authenticated users
         */
        $middleware->redirectUsersTo(function (Request $request): string {
            if ($request->user('user')) {
                return route('user.dashboard.index');
            }

            if ($request->user('writer')) {
                return route('writer.dashboard.index');
            }

            if ($request->user('manager')) {
                dd(":| YOU ARE MANAGER AND TRYING TO DO A FUCKING LOGIN INTO ANOTHER GUARD ACCOUNTS :|||||||| WTF DUDE!!!!!! GET BACK TO UR PANEL");
            }

            return route('index');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

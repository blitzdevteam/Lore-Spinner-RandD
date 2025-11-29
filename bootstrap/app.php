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

            return route('home');
        });

        /**
         * Custom redirects for authenticated users
         */
        $middleware->redirectUsersTo(function (Request $request): string {
            if ($request->user('user')) {
                return route('user.dashboard.index');
            } else if ($request->user('writer')) {
                return route('writer.dashboard.index');
            } else if ($request->user('manager')) {
                dd(":| YOU ARE MANAGER AND TRYING TO DO A FUCKING LOGIN INTO ANOTHER GUARD ACCOUNTS :|||||||| WTF DUDE!!!!!! GET BACK TO UR PANEL");
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

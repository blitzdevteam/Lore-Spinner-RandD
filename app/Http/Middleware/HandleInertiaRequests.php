<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    #[\Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'flash' => [
                'error' => $request->session()->get('error') ? Arr::wrap($request->session()->get('error')) : null,
                'success' => $request->session()->get('success') ? Arr::wrap($request->session()->get('success')) : null,
                'warning' => $request->session()->get('warning') ? Arr::wrap($request->session()->get('warning')) : null,
            ],
        ];
    }
}

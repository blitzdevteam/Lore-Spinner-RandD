<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Creator;

final class IndexController extends Controller
{
    public function __invoke()
    {
        return inertia('Index', [
            'creators' => fn () => Creator::query()
                ->select([
                    'id', 'username', 'first_name', 'last_name', 'avatar'
                ])
                ->take(3)
                ->latest()
                ->get()
                ->toResourceCollection()
        ]);
    }
}

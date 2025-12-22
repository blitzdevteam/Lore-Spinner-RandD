<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Writer;

final class IndexController extends Controller
{
    public function __invoke()
    {
        return inertia('Index', [
            'writers' => fn () => Writer::query()
                ->select(['id', 'first_name', 'last_name', 'avatar'])
                ->get(),
        ]);
    }
}

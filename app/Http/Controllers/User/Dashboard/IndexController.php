<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;

final class IndexController extends Controller
{
    public function __invoke(): void
    {
        dd(1);
    }
}

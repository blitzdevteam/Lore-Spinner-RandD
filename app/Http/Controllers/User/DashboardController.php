<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

final class DashboardController extends Controller
{
    public function __invoke(): \Illuminate\Http\RedirectResponse
    {
        return to_route('index');
    }
}

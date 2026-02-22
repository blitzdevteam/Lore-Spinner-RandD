<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

final class GameController extends Controller
{
    public function index()
    {
        return inertia('User/Games/Show');
    }

    public function show(): never
    {
        dd('show');
    }
}

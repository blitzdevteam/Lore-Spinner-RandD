<?php

namespace App\Http\Controllers\User\DashboardController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(): void
    {
        dd(1);
    }
}

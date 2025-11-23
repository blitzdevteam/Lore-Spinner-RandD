<?php

namespace App\Http\Controllers\User\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create()
    {
        return inertia('user/authentication/Login');
    }

    public function store()
    {
        dd(1);
    }
}

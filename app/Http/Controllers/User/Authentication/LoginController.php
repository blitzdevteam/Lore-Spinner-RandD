<?php

namespace App\Http\Controllers\User\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create()
    {
        return inertia('user/authentication/Login')->with('flash.success', ['this is a test message']);
    }

    public function store()
    {
        dd(1);
    }
}

<?php

namespace App\Http\Controllers\User\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create()
    {
        return inertia('User/Authentication/Register');
    }

    public function store()
    {
        dd(1);
    }
}

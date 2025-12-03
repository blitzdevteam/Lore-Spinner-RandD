<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

require __DIR__ . '/routes/user.php';

Route::get('/', Controllers\IndexController::class)->name('index');

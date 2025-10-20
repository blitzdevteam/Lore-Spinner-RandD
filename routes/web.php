<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('home');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('about');
})->name('about');

Route::get('/investors', function () {
    return Inertia::render('investors');
})->name('investors');

Route::get('/contact', function () {
    return Inertia::render('contact');
})->name('contact');

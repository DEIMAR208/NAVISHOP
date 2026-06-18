<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/panel', function () {
    return Inertia::render('Admin/adminu');
})->name('admin.panel');

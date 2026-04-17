<?php

use App\Http\Controllers\Web\backend\DashboardController;
use Illuminate\Support\Facades\Route;


/**
 *
 * Public
 */

Route::get('/', fn() => view('welcome'));
Route::get('/dashboard', function () {
    return view('backend.dashboard');
})->name('dashboard');

require __DIR__ . '/auth.php';

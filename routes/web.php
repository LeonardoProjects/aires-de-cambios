<?php

use App\Http\Controllers\AmbienteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum'])->get('/', function () {
    return Inertia::render('Home');
})->name('home');;

Route::middleware(['auth:sanctum'])->get('/herramienta', function () {
    return Inertia::render('Herramienta');
})->name('herramienta');;

Route::post('/ambiente/create', [AmbienteController::class, 'store'])->name('ambiente.store');

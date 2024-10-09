<?php

use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\ResultadosController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/herramienta', function () {
    return Inertia::render('Herramienta');
})->name('herramienta');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/ambiente/store', [AmbienteController::class, 'store'])->name('ambiente.store');
    Route::post('/ambiente/update', [AmbienteController::class, 'update'])->name('ambiente.update');
    Route::get(
        '/ambiente/getAll/{id}',
        [AmbienteController::class, 'obtenerAmbientes']
    )->name('ambiente.getAll');
});

Route::get('/pruebita', [ResultadosController::class, 'index']);

Route::post('/api/resultados', [ResultadosController::class, 'index'])->name('resultados.index');

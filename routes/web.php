<?php

use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ResultadosController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');
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
    Route::post('/ambiente/delete', [AmbienteController::class, 'delete'])->name('ambiente.delete');
    Route::get(
        '/ambiente/getAll/{id}',
        [AmbienteController::class, 'obtenerAmbientes']
    )->name('ambiente.getAll');
});

Route::post('/api/resultados', [ResultadosController::class, 'index'])->name('resultados.index');

Route::get('/countries-data', [ChartController::class, 'getCountriesData']);

Route::post('/survey', [SurveyController::class, 'store'])->middleware('auth');

Route::post('/check-survey-status', [SurveyController::class, 'checkSurveyStatus']);



<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TallerUserController;
use App\Http\Controllers\SuscripcionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', \App\Http\Middleware\AdminMiddleware::class])->name('dashboard');

Route::get('/documentacion', function () {
    return view('documentacion');
})->middleware(['auth', 'verified'])->name('documentacion');

Route::get('/suscripciones', function () {
    return view('suscripciones');
})->middleware(['auth', 'verified'])->name('suscripciones');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/taller/usuarios', [TallerUserController::class, 'store'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->name('taller.usuarios.store');

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::post('/taller/usuarios', [TallerUserController::class, 'store'])->name('taller.usuarios.store');
    Route::put('/taller/usuarios/{usuario}', [TallerUserController::class, 'update'])->name('taller.usuarios.update');
    Route::patch('/taller/usuarios/{usuario}/status', [TallerUserController::class, 'toggleStatus'])->name('taller.usuarios.status');
    Route::delete('/taller/usuarios/{usuario}', [TallerUserController::class, 'destroy'])->name('taller.usuarios.destroy');
    Route::get('/taller/suscripcion', [SuscripcionController::class, 'index'])->name('taller.suscripcion');
    Route::post('/taller/suscripcion/pagar', [SuscripcionController::class, 'pagar'])->name('taller.suscripcion.pagar');
});

require __DIR__.'/auth.php';

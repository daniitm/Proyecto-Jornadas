<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\PonenteController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\InscripcionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/pago-pendiente', function () {
    return view('pago-pendiente');
})->name('pago.pendiente');

// Nuevas rutas para PayPal
Route::get('/paypal/pay/{user_id}', [PagoController::class, 'payWithPayPal'])->name('paypal.pay');
Route::get('/paypal/success', [PagoController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('/paypal/cancel', [PagoController::class, 'paypalCancel'])->name('paypal.cancel');
Route::post('/paypal/ipn', [PagoController::class, 'handleIPN'])->name('paypal.ipn');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de eventos y ponentes para usuarios
    Route::get('/user/ponentes', [PonenteController::class, 'userIndex'])->name('user.ponentes.index');
    Route::get('/user/eventos', [EventoController::class, 'userIndex'])->name('user.eventos.index');

    // Nueva ruta para inscribirse en eventos
    Route::post('/eventos/{evento}/inscribirse', [InscripcionController::class, 'inscribirse'])
        ->name('eventos.inscribirse');

    Route::middleware(['can:admin'])->group(function () {
        Route::get('/eventos', [EventoController::class, 'vistaEventos'])->name('eventos.index');
        Route::get('/ponentes', [PonenteController::class, 'vistaPonentes'])->name('ponentes.index');
        Route::get('/inscripciones', [InscripcionController::class, 'index'])->name('inscripciones.index');
    });
});

require __DIR__.'/auth.php';
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PonenteController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\InscripcionController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Auth\RegisteredUserController;


    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    // Rutas de Ponentes
    Route::get('/ponentes', [PonenteController::class, 'index']);
    Route::get('/ponentes/{id}', [PonenteController::class, 'show']);
    Route::post('/ponentes', [PonenteController::class, 'store']);
    Route::put('/ponentes/{id}', [PonenteController::class, 'update']);
    Route::delete('/ponentes/{ponente}', [PonenteController::class, 'destroy']);

    // Rutas de Eventos
    Route::get('/eventos', [EventoController::class, 'index']);
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::get('/eventos/{id}', [EventoController::class, 'show']);
    Route::put('/eventos/{id}', [EventoController::class, 'update']);
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);

    // Rutas de Inscripciones
    Route::get('/inscripciones', [InscripcionController::class, 'index']);
    Route::get('/inscripciones/{id}', [InscripcionController::class, 'show']);
    Route::post('/inscripciones', [InscripcionController::class, 'store']);
    Route::put('/inscripciones/{id}', [InscripcionController::class, 'update']);
    Route::delete('/inscripciones/{id}', [InscripcionController::class, 'destroy']);

    // Rutas de Pagos
    Route::get('/pagos', [PagoController::class, 'index']);
    Route::get('/pagos/{id}', [PagoController::class, 'show']);
    Route::post('/pagos', [PagoController::class, 'store']);
    Route::put('/pagos/{id}', [PagoController::class, 'update']);
    Route::delete('/pagos/{id}', [PagoController::class, 'destroy']);


/*
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('ponentes', PonenteController::class);
    Route::apiResource('eventos', EventoController::class);
    Route::apiResource('inscripciones', InscripcionController::class);  
    Route::apiResource('pagos', PagoController::class);
});
*/

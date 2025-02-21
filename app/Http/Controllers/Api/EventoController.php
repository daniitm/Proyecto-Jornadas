<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use Carbon\Carbon;
use App\Models\Ponente;


class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::with('ponente')->get();
        return response()->json($eventos);
    }

    public function store(StoreEventoRequest $request)
    {
        $data = $request->validated();
        $data['hora_fin'] = Carbon::parse($data['hora_inicio'])->addMinutes(55)->format('H:i');

        // Verificar superposición
        $eventosSolapados = Evento::where('fecha', $data['fecha'])
            ->where('tipo', $data['tipo'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                    ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']]);
            })->exists();

        if ($eventosSolapados) {
            return response()->json(['error' => 'Ya existe un evento del mismo tipo en este horario'], 422);
        }

        // Verificar disponibilidad del ponente
        $ponenteOcupado = Evento::where('fecha', $data['fecha'])
            ->where('ponente_id', $data['ponente_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                    ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']]);
            })->exists();

        if ($ponenteOcupado) {
            return response()->json(['error' => 'El ponente ya está asignado a otro evento en este horario'], 422);
        }

        $evento = Evento::create($data);
        return response()->json($evento, 201);
    }

    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        return response()->json($evento, 200);
    }

    public function update(UpdateEventoRequest $request, $id)
    {
        $evento = Evento::findOrFail($id);
        $data = $request->validated();

        if (isset($data['hora_inicio'])) {
            $data['hora_fin'] = Carbon::parse($data['hora_inicio'])->addMinutes(55)->format('H:i');
        }

        // Verificar superposición
        $eventosSolapados = Evento::where('fecha', $data['fecha'])
            ->where('tipo', $data['tipo'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($data) {
                $query->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                    ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']]);
            })->exists();

        if ($eventosSolapados) {
            return response()->json(['error' => 'Ya existe un evento del mismo tipo en este horario'], 422);
        }

        // Verificar disponibilidad del ponente
        $ponenteOcupado = Evento::where('fecha', $data['fecha'])
            ->where('ponente_id', $data['ponente_id'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($data) {
                $query->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                    ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']]);
            })->exists();

        if ($ponenteOcupado) {
            return response()->json(['error' => 'El ponente ya está asignado a otro evento en este horario'], 422);
        }

        $evento->update($data);
        return response()->json($evento, 200);
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();
        return response()->json(null, 204);
    }

    public function vistaEventos()
    {
        $eventos = Evento::with('ponente')->get();
        $ponentes = Ponente::all(); // Obtener todos los ponentes disponibles
        return view('eventos.index', compact('eventos', 'ponentes'));
    }

    public function userIndex()
    {
        $eventos = Evento::all();
        return view('user.eventos.index', compact('eventos'));
    }

    public function welcomeView()
    {
        $eventos = Evento::with('ponente')->get();
        return view('welcome', compact('eventos'));
    }
}
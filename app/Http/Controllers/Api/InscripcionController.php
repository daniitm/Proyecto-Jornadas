<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInscripcionRequest;
use App\Http\Requests\UpdateInscripcionRequest;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with(['evento', 'user'])->get(); // Cargar las relaciones de evento y usuario
        return view('inscripciones.index', compact('inscripciones'));
    }

    public function store(StoreInscripcionRequest $request)
    {
        // Crear inscripción solo si pasa las validaciones
        $inscripcion = Inscripcion::create($request->validated());
        return response()->json($inscripcion, 201);
    }

    public function show(Inscripcion $inscripcion)
    {
        return $inscripcion->load(['user', 'evento']);
    }

    public function update(UpdateInscripcionRequest $request, Inscripcion $inscripcion)
    {
        $inscripcion->update($request->validated());
        return response()->json($inscripcion, 200);
    }

    public function destroy(Inscripcion $inscripcion)
    {
        $inscripcion->delete();
        return response()->json(null, 204);
    }

    public function inscribirse(Request $request, $eventoId)
    {
        $user = Auth::user();
        $evento = Evento::findOrFail($eventoId);

        // Verificar si el usuario ya está inscrito en este evento
        if (Inscripcion::where('user_id', $user->id)->where('evento_id', $eventoId)->exists()) {
            // Si ya está inscrito, no hacer nada
            return back()->with('error', 'Ya estás inscrito en este evento.');
        }

        // Contar el número de inscripciones actuales para este evento
        $inscripcionesCount = Inscripcion::where('evento_id', $eventoId)->count();

        // Verificar si el evento ha alcanzado su cupo máximo
        if ($inscripcionesCount >= $evento->cupo_maximo) {
            // Si no hay cupo, no registrar la inscripción
            return back()->with('error', 'No hay más cupos disponibles para este evento.');
        }

        // Contar inscripciones actuales del usuario para conferencias y talleres
        $conferenciasCount = Inscripcion::where('user_id', $user->id)
            ->whereHas('evento', function ($query) {
                $query->where('tipo', 'conferencia');
            })->count();

        $talleresCount = Inscripcion::where('user_id', $user->id)
            ->whereHas('evento', function ($query) {
                $query->where('tipo', 'taller');
            })->count();

        // Aplicar restricciones
        if ($evento->tipo === 'conferencia' && $conferenciasCount >= 5) {
            // Si el usuario ya tiene 5 conferencias, no permitir inscripción
            return back()->with('error', 'No puedes inscribirte a más de 5 conferencias.');
        }

        if ($evento->tipo === 'taller' && $talleresCount >= 4) {
            // Si el usuario ya tiene 4 talleres, no permitir inscripción
            return back()->with('error', 'No puedes inscribirte a más de 4 talleres.');
        }

        // Si pasa todas las verificaciones, registrar la inscripción
        Inscripcion::create([
            'user_id' => $user->id,
            'evento_id' => $evento->id,
            'tipo_inscripcion' => $user->tipo_inscripcion, // Se usa el tipo de inscripción del usuario
        ]);

        // Retornar éxito al usuario
        return back()->with('success', 'Inscripción realizada con éxito.');
    }
}
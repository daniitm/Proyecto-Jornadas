<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ponente;
use Illuminate\Http\Request;
use App\Http\Requests\StorePonenteRequest;
use App\Http\Requests\UpdatePonenteRequest;

use Illuminate\Support\Facades\Log;


class PonenteController extends Controller
{
    public function index()
    {
        Log::info('Accediendo a index de Ponentes');
        return Ponente::all();
    }

    public function store(StorePonenteRequest $request)
    {
        Log::info('Intentando crear un nuevo Ponente', $request->all());
        try {
            $ponente = Ponente::create($request->validated());
            Log::info('Ponente creado exitosamente', ['id' => $ponente->id]);
            return response()->json($ponente, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Ponente: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear Ponente'], 500);
        }
    }

    public function show($id)
    {
        $ponente = Ponente::findOrFail($id);
        return response()->json(['ponente' => $ponente]);
    }

    public function update(UpdatePonenteRequest $request, $id)
    {
        $ponente = Ponente::findOrFail($id);
        $ponente->update($request->validated());
        return response()->json(['message' => 'Ponente actualizado', 'ponente' => $ponente]);
    }

    public function destroy(Ponente $ponente)
    {
        Log::info('Intentando eliminar Ponente', ['id' => $ponente->id]);
        try {
            $ponente->delete();
            Log::info('Ponente eliminado exitosamente', ['id' => $ponente->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar Ponente: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar Ponente'], 500);
        }
    }

    public function vistaPonentes()
    {
        $ponentes = Ponente::all();
        return view('ponentes.index', compact('ponentes'));
    }

    public function userIndex()
    {
        $ponentes = Ponente::all();
        return view('user.ponentes.index', compact('ponentes'));
    }
}
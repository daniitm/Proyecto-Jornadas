<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ponente;
use Illuminate\Http\Request;
use App\Http\Requests\StorePonenteRequest;
use App\Http\Requests\UpdatePonenteRequest;
use Illuminate\Support\Facades\Storage;

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
        try {
            $data = $request->validated();
            $ponente = Ponente::create($data);
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
    $data = $request->validated();
    
    if ($request->hasFile('fotografia')) {
        $ponente->fotografia = $request->file('fotografia');
    } elseif ($request->has('fotografia') && is_null($request->fotografia)) {
        $ponente->fotografia = null;
    }
    
    $ponente->fill($data);
    $ponente->save();
    
    return response()->json(['message' => 'Ponente actualizado', 'ponente' => $ponente]);
}

public function destroy(Ponente $ponente)
{
    try {
        $ponente->delete();
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
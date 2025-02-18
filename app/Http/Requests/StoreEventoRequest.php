<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:conferencia,taller',
            'fecha' => 'required|date|in:2025-02-20,2025-02-21',
            'hora_inicio' => 'required|date_format:H:i|in:09:00,10:00,11:00,12:00,13:00,16:00,17:00,18:00,19:00,20:00',
            'cupo_maximo' => 'required|integer|min:1',
            'ponente_id' => 'required|exists:ponentes,id',
        ];
    }
}
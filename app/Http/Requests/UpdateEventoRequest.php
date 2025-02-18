<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|in:conferencia,taller',
            'fecha' => 'sometimes|required|date|in:2025-02-20,2025-02-21',
            'hora_inicio' => 'sometimes|required|date_format:H:i|in:09:00,10:00,11:00,12:00,13:00,16:00,17:00,18:00,19:00,20:00',
            'cupo_maximo' => 'sometimes|required|integer|min:1',
            'ponente_id' => 'sometimes|required|exists:ponentes,id',
        ];
    }
}
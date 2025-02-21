<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePonenteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'fotografia' => 'nullable|image|max:2048', // Máximo 2MB
            'areas_experiencia' => 'required|string',
            'enlace_red_social' => 'nullable|string|max:255',
        ];
    }
}
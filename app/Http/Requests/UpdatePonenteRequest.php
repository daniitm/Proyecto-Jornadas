<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePonenteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'sometimes|required|string|max:255',
            'fotografia' => 'nullable|string|max:255',
            'areas_experiencia' => 'sometimes|required|string',
            'enlace_red_social' => 'nullable|string|max:255',
        ];
    }
}
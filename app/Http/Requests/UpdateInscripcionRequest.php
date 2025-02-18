<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInscripcionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipo_inscripcion' => 'sometimes|required|in:presencial,virtual,gratuita',
        ];
    }
}
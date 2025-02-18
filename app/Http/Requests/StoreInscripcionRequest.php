<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInscripcionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'evento_id' => 'required|exists:eventos,id',
            'tipo_inscripcion' => 'required|in:presencial,virtual,gratuita',
        ];
    }
}
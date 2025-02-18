<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|numeric|min:0',
            'estado' => 'required|string',
            'transaction_id' => 'required|string|unique:pagos',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->rol === 'admin'
            && auth()->user()->id_instalacion === \App\Models\Instalacion::where('slug', request()->slug_instalacion)->firstOrFail()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del banco es requerido',
            'name.string' => 'El nombre del banco debe ser una cadena de texto',
            'name.max' => 'El nombre del banco no debe ser mayor a 255 caracteres',
        ];
    }
}

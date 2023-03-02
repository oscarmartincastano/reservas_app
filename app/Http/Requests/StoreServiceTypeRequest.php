<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceTypeRequest extends FormRequest
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
            'iva' => 'required|numeric|between:0,99.99',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.string' => 'El campo nombre debe ser una cadena de texto',
            'name.max' => 'El campo nombre no puede tener más de 255 caracteres',
            'iva.required' => 'El campo IVA es obligatorio',
            'iva.numeric' => 'El campo IVA debe ser un número',
            'iva.between' => 'El campo IVA debe estar entre 0 y 99.99',
        ];
    }
}

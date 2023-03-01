<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSponsorRequest extends FormRequest
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
            'website' => 'nullable|url',
            'logo' => 'required'
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'website.required' => 'El sitio web es obligatorio',
            'website.url' => 'El sitio web debe ser una URL válida',
            'logo.required' => 'El logo es obligatorio',
        ];
    }
}

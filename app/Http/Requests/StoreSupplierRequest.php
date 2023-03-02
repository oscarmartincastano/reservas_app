<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'cif' => 'nullable|string|max:255|unique:suppliers',
            'cp' => 'nullable|string|max:5',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'address.string' => 'La dirección debe ser una cadena de texto',
            'address.max' => 'La dirección no puede tener más de 255 caracteres',
            'phone.string' => 'El teléfono debe ser una cadena de texto',
            'phone.max' => 'El teléfono no puede tener más de 255 caracteres',
            'email.string' => 'El email debe ser una cadena de texto',
            'email.email' => 'El email debe ser un email válido',
            'email.max' => 'El email no puede tener más de 255 caracteres',
            'cif.string' => 'El CIF debe ser una cadena de texto',
            'cif.max' => 'El CIF no puede tener más de 255 caracteres',
            'cif.unique' => 'El CIF ya está en uso',
            'cp.string' => 'El código postal debe ser una cadena de texto',
            'cp.max' => 'El código postal no puede tener más de 5 caracteres',
            'city.string' => 'La ciudad debe ser una cadena de texto',
            'city.max' => 'La ciudad no puede tener más de 255 caracteres',
            'province.string' => 'La provincia debe ser una cadena de texto',
            'province.max' => 'La provincia no puede tener más de 255 caracteres',
            'notes.string' => 'Las notas deben ser una cadena de texto',
            'notes.max' => 'Las notas no pueden tener más de 255 caracteres',
            'instalacion_id.required' => 'La instalación es obligatoria',
            'instalacion_id.exists' => 'La instalación no existe',
        ];
    }
}

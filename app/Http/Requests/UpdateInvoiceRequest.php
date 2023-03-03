<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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

            'supplier_id' => 'required|integer|exists:suppliers,id',
            'bank_id' => 'required|integer|exists:banks,id',
            'number' => 'nullable|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'paid' => 'nullable|numeric|min:0',
            'paid_at' => 'nullable|date|before_or_equal:today',
            'invoiceLines' => 'required|array|min:1',
            'invoiceLines.*.concept' => 'required|string|max:255',
            'invoiceLines.*.service_type_id' => 'required|integer|exists:service_types,id',
            'invoiceLines.*.base' => 'required|numeric|min:0',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'El proveedor es requerido',
            'supplier_id.integer' => 'El proveedor debe ser un número entero',
            'supplier_id.exists' => 'El proveedor seleccionado no existe',
            'bank_id.required' => 'El banco es requerido',
            'bank_id.integer' => 'El banco debe ser un número entero',
            'bank_id.exists' => 'El banco seleccionado no existe',
            'number.string' => 'El número de factura debe ser una cadena de texto',
            'number.max' => 'El número de factura no debe ser mayor a 255 caracteres',
            'date.required' => 'La fecha de la factura es requerida',
            'date.date' => 'La fecha de la factura debe ser una fecha válida',
            'date.before_or_equal' => 'La fecha de la factura no puede ser posterior a la fecha actual',
            'paid.numeric' => 'El importe pagado debe ser un número',
            'paid.min' => 'El importe pagado no puede ser negativo',
            'paid_at.date' => 'La fecha de pago debe ser una fecha válida',
            'paid_at.before_or_equal' => 'La fecha de pago no puede ser posterior a la fecha actual',
            'invoiceLines.required' => 'La factura debe tener al menos una línea',
            'invoiceLines.array' => 'La factura debe tener al menos una línea',
            'invoiceLines.min' => 'La factura debe tener al menos una línea',
            'invoiceLines.*.concept.required' => 'El concepto es requerido',
            'invoiceLines.*.concept.string' => 'El concepto debe ser una cadena de texto',
            'invoiceLines.*.concept.max' => 'El concepto no debe ser mayor a 255 caracteres',
            'invoiceLines.*.service_type_id.required' => 'El tipo de servicio es requerido',
            'invoiceLines.*.service_type_id.integer' => 'El tipo de servicio debe ser un número entero',
            'invoiceLines.*.service_type_id.exists' => 'El tipo de servicio seleccionado no existe',
            'invoiceLines.*.base.required' => 'El importe base es requer ido',
            'invoiceLines.*.base.numeric' => 'El importe base debe ser un número',
            'invoiceLines.*.base.min' => 'El importe base no puede ser negativo',
            'file.file' => 'El archivo adjunto debe ser un archivo',
            'file.mimes' => 'El archivo adjunto debe ser un archivo PDF',
            'file.max' => 'El archivo adjunto no debe ser mayor a 2MB',
        ];
    }
}

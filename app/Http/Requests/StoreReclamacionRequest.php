<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReclamacionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'tipo_documento' => 'required|string|in:DNI,RUC,CE,PAS',
            'numero_documento' => 'required|string|max:20',
            'telefono' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'departamento' => 'required|string',
            'provincia' => 'required|string',
            'distrito' => 'required|string',
            'direccion' => 'required|string|max:255',
            'detalle' => 'required|string|max:300',
            'pedido_cliente' => 'required|string|max:300',
            'tipo_bien' => 'required|string',
            'descripcion' => 'required|string',
            'tipo_reclamacion' => 'required|string',
            'canal' => 'required|string',
            'motivo' => 'required|string',
            'submotivo' => 'required|string',
            'comprobantes'   => 'nullable|array|max:4',
            'comprobantes.*' => 'file|mimes:jpg,jpeg,png,pdf|max:4096', // 4096 KB = 4 MB
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El campo :attribute debe ser un email válido.',
            'max' => 'El campo :attribute no debe exceder los :max caracteres.',
            'in' => 'El campo :attribute no es válido.',
        ];
    }
}

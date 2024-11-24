<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string',
            'unit_price' => 'nullable|numeric|between:0,99999.99',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'El :attribute es obligatorio.',
            'full_name.string' => 'El :attribute debe contener caracteres válidos',

            'unit_price.numeric' => 'El :attribute debe ser un número.',
            'unit_price.between' => 'El :attribute esta fuera del rango numérico.',

            'image.image' => 'La :attribute debe ser un formato de imagen correcto',

            'category_id.exists' => 'El :attribute no existe en la base de datos.',
            'category_id.required' => 'La :attribute es obligatoria.',
        ];
    }

    public function attributes()
    {
        return [
            'full_name' => 'Nombre Completo',
            'unit_price' => 'precio unitario',
            'image' => 'imagen',
            'category_id' => 'categoría',

        ];
    }
}

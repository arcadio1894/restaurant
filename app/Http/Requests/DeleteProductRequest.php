<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'El :attribute es obligatorio.',
            'product_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'product_id' => 'id del producto'
        ];
    }
}

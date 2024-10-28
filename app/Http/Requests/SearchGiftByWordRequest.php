<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchGiftByWordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'query' => 'required|string|max:255',
            'limit' => 'integer|min:1|max:50',
            'offset' => 'integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'query.required' => 'La consulta es obligatoria.',
            'query.string' => 'La consulta debe ser una cadena de texto.',
            'query.max' => 'La consulta no puede tener más de 255 caracteres.',
            
            'limit.integer' => 'El límite debe ser un número entero.',
            'limit.min' => 'El límite debe ser al menos 1.',
            'limit.max' => 'El límite no puede ser mayor a 50.',

            'offset.integer' => 'El desplazamiento debe ser un número entero.',
            'offset.min' => 'El desplazamiento no puede ser negativo.',
        ];
    }

    public function validationData()
    {
        return $this->query();
    }

    public function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 400)
        );
    }
}

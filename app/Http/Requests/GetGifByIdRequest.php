<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetGifByIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El id es requerido.',
            'id.string' => 'El id debe de ser string.',
            'id.max' => 'El límite no puede ser mayor a 50.',
        ];
    }

    public function validationData()
    {
        return array_merge($this->query(), [
            'id' => $this->route('id'),
        ]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 400)
        );
    }
}

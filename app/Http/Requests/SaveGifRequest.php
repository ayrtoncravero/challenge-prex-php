<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SaveGifRequest extends FormRequest
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
            'gif_id' => 'required|string|max:50',
            'alias' => 'required|string|max:50',
            'user_id' => 'required|integer|max:10',
        ];
    }

    public function messages()
    {
        return [
            'gif_id.required' => 'El gif es requerido.',
            'gif_id.string' => 'El gif debe de ser string.',
            'gif_id.max' => 'El límite no puede ser mayor a 50.',

            'alias.required' => 'El alias es requerido.',
            'alias.string' => 'El alias debe de ser string.',
            'alias.max' => 'El límite no puede ser mayor a 50.',

            'user_id.required' => 'El usuario es requerido.',
            'user_id.integer' => 'El usuario debe de ser string.',
            'user_id.max' => 'El límite no puede ser mayor a 10.',
        ];
    }

    public function validationData()
    {
        return $this->all();
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 400)
        );
    }
}

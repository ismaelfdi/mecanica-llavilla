<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItemRequest extends FormRequest
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
            'descripcion' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:60|unique:items,codigo',
            'tipo' => ['required', Rule::in(['labor', 'part'])],
            'precio_unitario' => 'required|numeric|min:0',
            'tasa_impuesto' => 'nullable|numeric|min:0|max:100',
        ];
    }
}

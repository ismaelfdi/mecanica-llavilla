<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Permitimos que cualquier usuario autenticado pueda crear clientes
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
            'nombre' => 'required|string|max:150',
            'documento' => 'nullable|string|max:50|unique:clientes,documento',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150|unique:clientes,email',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ];
    }
}

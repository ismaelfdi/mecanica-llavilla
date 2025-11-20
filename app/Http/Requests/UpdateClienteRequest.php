<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
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
        // Obtenemos el ID del cliente desde la ruta (ej: /clientes/5/edit)
        $clienteId = $this->route('cliente')->id;

        return [
            'nombre' => 'required|string|max:150',
            // La regla unique debe ignorar el registro actual
            'documento' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('clientes', 'documento')->ignore($clienteId),
            ],
            'telefono' => 'nullable|string|max:50',
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('clientes', 'email')->ignore($clienteId),
            ],
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ];
    }
}

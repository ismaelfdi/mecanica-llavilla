<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehiculoRequest extends FormRequest
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
        $vehiculoId = $this->route('vehiculo')->id;

        return [
            'cliente_id' => 'required|exists:clientes,id',
            'matricula' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehiculos', 'matricula')->ignore($vehiculoId),
            ],
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:120',
            'version' => 'nullable|string|max:120',
            'numero_bastidor' => [
                'nullable',
                'string',
                'max:32',
                Rule::unique('vehiculos', 'numero_bastidor')->ignore($vehiculoId),
            ],
            'anio' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'kilometraje' => 'nullable|integer|min:0',
            'tipo_combustible' => 'nullable|string|max:30',
            'transmision' => 'nullable|string|max:30',
        ];
    }
}

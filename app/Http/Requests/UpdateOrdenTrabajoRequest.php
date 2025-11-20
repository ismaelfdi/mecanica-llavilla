<?php

namespace App\Http\Requests;

use App\Models\OrdenTrabajo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrdenTrabajoRequest extends FormRequest
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
            'estado' => ['required', Rule::in([
                OrdenTrabajo::ESTADO_ABIERTA,
                OrdenTrabajo::ESTADO_EN_PROCESO,
                OrdenTrabajo::ESTADO_COMPLETADA,
                OrdenTrabajo::ESTADO_CANCELADA,
            ])],
            // ¡AÑADE ESTA REGLA!
            'estado_pago' => ['required', Rule::in([
                OrdenTrabajo::PAGO_NO_PAGADA,
                OrdenTrabajo::PAGO_PARCIAL,
                OrdenTrabajo::PAGO_PAGADA,
            ])],
            'diagnostico' => 'nullable|string|max:5000',
            'notas' => 'nullable|string|max:5000',
        ];
    }
}

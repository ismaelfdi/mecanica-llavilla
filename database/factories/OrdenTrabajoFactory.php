<?php

namespace Database\Factories;

use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdenTrabajoFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = OrdenTrabajo::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehiculo_id' => Vehiculo::factory(), // Asocia un vehículo automáticamente
            'estado' => $this->faker->randomElement(['abierta', 'en_proceso', 'completada', 'cancelada']),
            'estado_pago' => $this->faker->randomElement(['no_pagada', 'parcial', 'pagada']),
            'abierta_en' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'cerrada_en' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'queja_cliente' => $this->faker->sentence(),
            'diagnostico' => $this->faker->text(),
            'notas' => $this->faker->optional()->text(),
            'moneda' => 'EUR',
        ];
    }
}

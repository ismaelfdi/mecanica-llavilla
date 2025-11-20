<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'documento' => $this->faker->unique()->randomNumber(8),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'direccion' => $this->faker->address(),
            'ciudad' => $this->faker->city(),
            'provincia' => $this->faker->state(),
            'pais' => $this->faker->country(),
            'codigo_postal' => $this->faker->postcode(),
            'meta' => json_encode(['preferencias' => 'Cliente recurrente']),
        ];
    }
}

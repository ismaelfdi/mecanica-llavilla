<?php

namespace Database\Factories;

use App\Models\Vehiculo;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = Vehiculo::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(), // Asocia un cliente automáticamente
            'matricula' => $this->faker->unique()->bothify('??####'),
            'marca' => $this->faker->randomElement(['Ford', 'Chevrolet', 'Nissan', 'Toyota']),
            'modelo' => $this->faker->word(),
            'version' => $this->faker->optional()->word(),
            'numero_bastidor' => $this->faker->unique()->bothify('#################'),
            'anio' => $this->faker->year(),
            'color' => $this->faker->safeColorName(),
            'kilometraje' => $this->faker->numberBetween(1000, 200000),
            'tipo_combustible' => $this->faker->randomElement(['gasolina', 'diesel', 'hibrido', 'electrico']),
            'transmision' => $this->faker->randomElement(['manual', 'automatica']),
            'meta' => json_encode(['neumaticos' => '225/45R17']),
        ];
    }
}

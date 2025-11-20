<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo' => $this->faker->randomElement(['labor', 'part']),
            'codigo' => $this->faker->unique()->bothify('#####-??'),
            'descripcion' => $this->faker->sentence(),
            'precio_unitario' => $this->faker->randomFloat(2, 5, 500),
            'tasa_impuesto' => $this->faker->randomElement([21.00, 10.00, 4.00, 0.00]),
        ];
    }
}

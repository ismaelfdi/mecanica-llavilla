<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OrdenTrabajo;
use App\Models\Item;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed de la base de datos de la aplicación.
     */
    public function run(): void
    {
        // 1. Crea 20 clientes
        $clientes = Cliente::factory()->count(20)->create();

        // 2. Por cada cliente, crea 1 o 2 vehículos
        $clientes->each(function ($cliente) {
            Vehiculo::factory()->count(rand(1, 2))->create([
                'cliente_id' => $cliente->id,
            ]);
        });

        // 3. Crea 100 ítems (partes y servicios)
        $items = Item::factory()->count(100)->create();

        // 4. Crea 50 órdenes de trabajo
        $ordenes = OrdenTrabajo::factory()->count(50)->create();

        // 5. Por cada orden de trabajo, asocia 2 a 5 ítems
        $ordenes->each(function ($orden) use ($items) {
            $itemsAleatorios = $items->random(rand(2, 5));
            foreach ($itemsAleatorios as $item) {
                // Adjunta los ítems a la orden de trabajo con datos de la tabla pivotante
                $orden->items()->attach($item->id, [
                    'cantidad' => rand(1, 3),
                    'precio_aplicado' => $item->precio_unitario,
                    'descuento' => 0,
                    'linea_total' => $item->precio_unitario * rand(1, 3),
                ]);
            }
        });
    }
}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <a href="{{ route('ordenes-trabajo.index', ['estado' => App\Models\OrdenTrabajo::ESTADO_ABIERTA]) }}" class="bg-blue-100 dark:bg-blue-900 p-6 rounded-lg text-center cursor-pointer hover:shadow-lg transition">
                            <h3 class="text-3xl font-bold">{{ $ordenesAbiertas }}</h3>
                            <p class="text-sm">Órdenes Abiertas</p>
                        </a>
                        <a href="{{ route('ordenes-trabajo.index', ['estado' => App\Models\OrdenTrabajo::ESTADO_EN_PROCESO]) }}" class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-lg text-center cursor-pointer hover:shadow-lg transition">
                            <h3 class="text-3xl font-bold">{{ $ordenesEnProceso }}</h3>
                            <p class="text-sm">Órdenes en Proceso</p>
                        </a>
                        <a href="{{ route('ordenes-trabajo.index', ['estado' => App\Models\OrdenTrabajo::ESTADO_COMPLETADA]) }}" class="bg-green-100 dark:bg-green-900 p-6 rounded-lg text-center cursor-pointer hover:shadow-lg transition">
                            <h3 class="text-3xl font-bold">{{ $ordenesCompletadas }}</h3>
                            <p class="text-sm">Órdenes Completadas</p>
                        </a>
                        <div class="bg-indigo-100 dark:bg-indigo-900 p-6 rounded-lg text-center cursor-pointer">
                            <h3 class="text-3xl font-bold">{{ number_format($ingresosMes, 2) }} €</h3>
                            <p class="text-sm">Ingresos (Mes)</p>
                        </div>
                    </div>


                    <!-- Graficos dinamicos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ingresos: Mano de Obra vs. Repuestos</h3>

                            <div style="position: relative; height:40vh; width:100%">
                                <canvas id="ingresosTipoChart"></canvas>
                            </div>

                        </div>

                    </div>
                    <!-- Fin de los graficos -->


                    <h3 class="text-lg font-semibold mb-4">Órdenes Recientes</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"># Orden</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Vehículo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($ordenesRecientes as $orden)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $orden->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ Str::upper($orden->vehiculo->matricula) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $orden->vehiculo->cliente->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $colorClass = match($orden->estado) {
                                                'abierta' => 'bg-blue-200 text-blue-800',
                                                'en_proceso' => 'bg-yellow-200 text-yellow-800',
                                                'completada' => 'bg-green-200 text-green-800',
                                                'cancelada' => 'bg-red-200 text-red-800',
                                                default => 'bg-gray-200 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                        {{ ucfirst($orden->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($orden->total_general, 2) }} €</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm">No hay órdenes recientes.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>



    <script>

        document.addEventListener('DOMContentLoaded', function () {

            // Datos del controlador
            const ingresosManoObra = parseFloat("{{ $ingresosPorTipo->mano_obra ?? 0 }}");
            const ingresosRepuestos = parseFloat("{{ $ingresosPorTipo->repuestos ?? 0 }}");

            // ¡AÑADE ESTAS LÍNEAS!
            console.log('Datos del gráfico:', {
                manoObra: ingresosManoObra,
                repuestos: ingresosRepuestos
            });

            const ctx = document.getElementById('ingresosTipoChart');

            if (ctx) { // Asegúrate de que el elemento exista antes de inicializar el gráfico
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Mano de Obra', 'Repuestos'],
                        datasets: [{
                            label: 'Total de Ingresos',
                            data: [ingresosManoObra, ingresosRepuestos],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.5)',
                                'rgba(34, 197, 94, 0.5)',
                            ],
                            borderColor: [
                                'rgba(59, 130, 246, 1)',
                                'rgba(34, 197, 94, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed) {
                                            label += new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(context.parsed);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

        });
    </script>


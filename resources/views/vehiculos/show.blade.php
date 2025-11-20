<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ficha del Vehículo: {{ Str::upper($vehiculo->matricula) }}
            </h2>
            <a href="{{ route('vehiculos.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                &larr; Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- MENSAJE DE ÉXITO -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- SECCIÓN DE DATOS DEL VEHÍCULO -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Detalles del Vehículo</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><p><strong>Matrícula:</strong> {{ Str::upper($vehiculo->matricula) }}</p></div>
                        <div><p><strong>Marca:</strong> {{ $vehiculo->marca ?? 'N/A' }}</p></div>
                        <div><p><strong>Modelo:</strong> {{ $vehiculo->modelo ?? 'N/A' }}</p></div>
                        <div><p><strong>Año:</strong> {{ $vehiculo->anio ?? 'N/A' }}</p></div>
                        <div><p><strong>Kilometraje:</strong> {{ $vehiculo->kilometraje ? number_format($vehiculo->kilometraje, 0, ',', '.') . ' km' : 'N/A' }}</p></div>
                        <div><p><strong>Color:</strong> {{ $vehiculo->color ?? 'N/A' }}</p></div>
                        <div class="md:col-span-3"><p><strong>Nº Bastidor:</strong> {{ $vehiculo->numero_bastidor ?? 'N/A' }}</p></div>
                        <div class="md:col-span-3">
                            <p><strong>Propietario:</strong>
                                <a href="{{ route('clientes.show', $vehiculo->cliente) }}" class="text-indigo-600 hover:underline">
                                    {{ $vehiculo->cliente->nombre }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-6 flex justify-end items-center space-x-4">
                        <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Editar Vehículo
                        </a>

                        <form method="POST" action="{{ route('vehiculos.destroy', $vehiculo) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de que quieres eliminar este vehículo? Esta acción no se puede deshacer.')">
                                Eliminar Vehículo
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN DE ÓRDENES DE TRABAJO -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Historial de Órdenes ({{ $vehiculo->ordenesTrabajo->count() }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"># Orden</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha Apertura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($vehiculo->ordenesTrabajo as $orden)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $orden->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $orden->abierta_en ? $orden->abierta_en->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $orden->estado }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ number_format($orden->total_general, 2) }} {{ $orden->moneda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Ver Orden</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center">Este vehículo no tiene órdenes de trabajo.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

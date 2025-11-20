<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ficha del Cliente: {{ $cliente->nombre }}
            </h2>
            <a href="{{ route('clientes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
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

            <!-- SECCIÓN DE DATOS DEL CLIENTE -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Datos de Contacto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><p><strong>Nombre:</strong> {{ $cliente->nombre }}</p></div>
                        <div><p><strong>Documento:</strong> {{ $cliente->documento ?? 'N/A' }}</p></div>
                        <div><p><strong>Email:</strong> {{ $cliente->email ?? 'N/A' }}</p></div>
                        <div><p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'N/A' }}</p></div>
                        <div class="col-span-2"><p><strong>Dirección:</strong> {{ $cliente->direccion ?? 'N/A' }}</p></div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-6 flex justify-end items-center space-x-4">
                        <a href="{{ route('clientes.edit', $cliente) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Editar Cliente
                        </a>
                        <form method="POST" action="{{ route('clientes.destroy', $cliente) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente? Esta acción no se puede deshacer.')">
                                Eliminar Cliente
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN DE VEHÍCULOS -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Vehículos ({{ $cliente->vehiculos->count() }})</h3>

                    <a href="{{ route('vehiculos.create', ['cliente_id' => $cliente->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                        [+] Añadir Vehículo
                    </a>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Matrícula</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Marca</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Modelo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Año</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($cliente->vehiculos as $vehiculo)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $vehiculo->matricula }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $vehiculo->marca }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $vehiculo->modelo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $vehiculo->anio }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center">Este cliente no tiene vehículos registrados.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN DE ÓRDENES DE TRABAJO -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Historial de Órdenes ({{ $cliente->ordenesTrabajo->count() }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"># Orden</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Vehículo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha Apertura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($cliente->ordenesTrabajo as $orden)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $orden->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }} ({{$orden->vehiculo->matricula}})</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $orden->abierta_en->format('d/m/Y') }}</td>
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">{{ $orden->estado }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ number_format($orden->total_general, 2) }} {{ $orden->moneda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('ordenes-trabajo.show', $orden->id) }}" class="text-blue-500 hover:text-blue-700">
                                            Ver Orden
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center">Este cliente no tiene órdenes de trabajo.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Orden de Trabajo #{{ $ordenTrabajo->id }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('ordenes-trabajo.invoice', $ordenTrabajo) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Generar Factura PDF
                </a>
                <a href="{{ route('ordenes-trabajo.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                    &larr; Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <form method="POST" action="{{ route('ordenes-trabajo.update', $ordenTrabajo) }}">
                        @csrf
                        @method('PUT')
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Detalles de la Orden</h3>
                                <p><strong>Vehículo:</strong>
                                    @if($ordenTrabajo->vehiculo)
                                        <a href="{{ route('vehiculos.show', $ordenTrabajo->vehiculo->id) }}" class="text-indigo-600 hover:underline">
                                            {{ $ordenTrabajo->vehiculo->marca }} {{ $ordenTrabajo->vehiculo->modelo }} ({{ Str::upper($ordenTrabajo->vehiculo->matricula) }})
                                        </a>
                                    @else
                                        <span class="text-red-500">Vehículo no encontrado</span>
                                    @endif
                                </p>
                                <p><strong>Cliente:</strong>
                                    @if($ordenTrabajo->vehiculo?->cliente)
                                        <a href="{{ route('clientes.show', $ordenTrabajo->vehiculo->cliente->id) }}" class="text-indigo-600 hover:underline">
                                            {{ $ordenTrabajo->vehiculo->cliente->nombre }}
                                        </a>
                                    @else
                                        <span class="text-red-500">Cliente no encontrado</span>
                                    @endif
                                </p>

                                <hr class="my-4 border-gray-200 dark:border-gray-700">
                                <div>
                                    <h4 class="font-semibold">Queja del Cliente:</h4>
                                    <p class="mt-1 text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $ordenTrabajo->queja_cliente }}</p>
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="diagnostico" value="Diagnóstico del Mecánico" />
                                    <textarea id="diagnostico" name="diagnostico" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('diagnostico', $ordenTrabajo->diagnostico) }}</textarea>
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="notas" value="Notas Internas" />
                                    <textarea id="notas" name="notas" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('notas', $ordenTrabajo->notas) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Actualizar Estado</h3>
                                <div>
                                    <x-input-label for="estado" value="Estado de la Orden" />
                                    <select id="estado" name="estado" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        <option value="abierta" @selected($ordenTrabajo->estado == 'abierta')>Abierta</option>
                                        <option value="en_proceso" @selected($ordenTrabajo->estado == 'en_proceso')>En Proceso</option>
                                        <option value="completada" @selected($ordenTrabajo->estado == 'completada')>Completada</option>
                                        <option value="cancelada" @selected($ordenTrabajo->estado == 'cancelada')>Cancelada</option>
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="estado_pago" value="Estado de Pago" />
                                    <select id="estado_pago" name="estado_pago" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        <option value="no_pagada" @selected($ordenTrabajo->estado_pago == 'no_pagada')>No Pagada</option>
                                        <option value="parcial" @selected($ordenTrabajo->estado_pago == 'parcial')>Parcial</option>
                                        <option value="pagada" @selected($ordenTrabajo->estado_pago == 'pagada')>Pagada</option>
                                    </select>
                                </div>
                                <div class="mt-6">
                                    <x-primary-button class="w-full justify-center">
                                        Actualizar Orden
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Añadir Concepto</h3>

                            <form method="POST" action="{{ route('ordenes-trabajo.items.store', $ordenTrabajo) }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                    <div class="md:col-span-3">
                                        <x-input-label for="select-item" value="Ítem del Catálogo" />
                                        <select id="select-item" name="item_id">
                                            <option value="">-- Seleccionar --</option>
                                            @foreach($items_catalogo as $item)
                                                <option value="{{ $item->id }}" data-precio="{{ $item->precio_unitario }}">{{ $item->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-span-1">
                                        <x-input-label for="cantidad" value="Cantidad" />
                                        <x-text-input id="cantidad" name="cantidad" type="number" class="mt-1 block w-full" value="1" step="0.01" required />
                                    </div>
                                    <div class="md:col-span-1">
                                        <x-input-label for="precio_aplicado" value="Precio (€)" />
                                        <x-text-input id="precio_aplicado" name="precio_aplicado" type="number" class="mt-1 block w-full" step="0.01" required />
                                    </div>
                                    <div class="md:col-span-1 flex items-end">
                                        <x-primary-button class="w-full justify-center">Añadir</x-primary-button>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('item_id')" class="mt-2" />
                                <x-input-error :messages="$errors->get('cantidad')" class="mt-2" />
                                <x-input-error :messages="$errors->get('precio_aplicado')" class="mt-2" />
                            </form>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">

                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Conceptos Añadidos</h3>
                            <div class="mt-4 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                            <thead>
                                            <tr>
                                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold">Descripción</th>
                                                <th class="px-3 py-3.5 text-left text-sm font-semibold">Cant.</th>
                                                <th class="px-3 py-3.5 text-left text-sm font-semibold">Precio</th>
                                                <th class="px-3 py-3.5 text-left text-sm font-semibold">Total</th>
                                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-0"><span class="sr-only">Acciones</span></th>
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @forelse ($ordenTrabajo->items as $item)
                                                <tr>
                                                    <td class="py-4 pl-4 pr-3 text-sm">{{ $item->descripcion }}</td>
                                                    <td class="px-3 py-4 text-sm">{{ $item->pivot->cantidad }}</td>
                                                    <td class="px-3 py-4 text-sm">{{ number_format($item->pivot->precio_aplicado, 2, ',', '.') }} €</td>
                                                    <td class="px-3 py-4 text-sm font-semibold">{{ number_format($item->pivot->linea_total, 2, ',', '.') }} €</td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                        <form method="POST" action="{{ route('ordenes-trabajo.items.destroy', [$ordenTrabajo, $item->pivot->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de que quieres eliminar este concepto?')">Eliminar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-4 text-center text-sm text-gray-500">Aún no se han añadido conceptos a esta orden.</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Totales</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span>Mano de Obra:</span>
                                    <span>{{ number_format($ordenTrabajo->total_mano_obra, 2, ',', '.') }} €</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Repuestos:</span>
                                    <span>{{ number_format($ordenTrabajo->total_repuestos, 2, ',', '.') }} €</span>
                                </div>
                                <hr class="my-2 border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between font-bold text-lg">
                                    <span>TOTAL:</span>
                                    <span>{{ number_format($ordenTrabajo->total_general, 2, ',', '.') }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let tomSelectItem = new TomSelect('#select-item',{
                    placeholder: 'Escribe para buscar un ítem...',
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });

                tomSelectItem.on('change', function(value) {
                    const precioInput = document.getElementById('precio_aplicado');
                    if (!value) {
                        precioInput.value = '';
                        return;
                    }

                    const optionData = tomSelectItem.options[value];

                    if (optionData && optionData.precio) {
                        precioInput.value = optionData.precio;
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>

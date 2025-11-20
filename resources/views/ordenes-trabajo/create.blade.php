<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nueva Orden de Trabajo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('ordenes-trabajo.store') }}">
                        @csrf

                        <!-- Vehículo -->
                        <div>
                            <x-input-label for="vehiculo_id" :value="__('Vehículo')" />
                            {{-- El id="select-vehiculo" es importante para que lo encuentre el JS --}}
                            <select id="select-vehiculo" name="vehiculo_id" placeholder="Escribe para buscar matrícula, marca, cliente...">
                                <option value="">-- Selecciona un vehículo --</option>
                                @foreach($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id }}" {{ old('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                        {{ Str::upper($vehiculo->matricula) }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }} (Prop: {{ $vehiculo->cliente->nombre }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('vehiculo_id')" class="mt-2" />
                        </div>

                        <!-- Queja del Cliente -->
                        <div class="mt-4">
                            <x-input-label for="queja_cliente" :value="__('Queja / Problema reportado por el cliente')" />
                            <textarea id="queja_cliente" name="queja_cliente" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('queja_cliente') }}</textarea>
                            <x-input-error :messages="$errors->get('queja_cliente')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('ordenes-trabajo.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Guardar y Abrir Orden') }}
                            </x-primary-button>
                        </div>
                    </form>

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
                new TomSelect('#select-vehiculo',{
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>

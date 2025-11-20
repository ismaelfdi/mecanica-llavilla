<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Nuevo Vehículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('vehiculos.store') }}">
                        @csrf

                        <!-- Cliente -->
                        <div>
                            <x-input-label for="cliente_id" :value="__('Cliente Propietario')" />
                            <select id="cliente_id" name="cliente_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Selecciona un cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected(old('cliente_id', $selectedClienteId) == $cliente->id)>
                                        {{ $cliente->nombre }} ({{ $cliente->documento ?? 'Sin doc.' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
                        </div>

                        <!-- Matrícula -->
                        <div class="mt-4">
                            <x-input-label for="matricula" :value="__('Matrícula')" />
                            <x-text-input id="matricula" class="block mt-1 w-full uppercase" type="text" name="matricula" :value="old('matricula')" required />
                            <x-input-error :messages="$errors->get('matricula')" class="mt-2" />
                        </div>

                        <!-- Marca y Modelo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="marca" :value="__('Marca')" />
                                <x-text-input id="marca" class="block mt-1 w-full" type="text" name="marca" :value="old('marca')" />
                                <x-input-error :messages="$errors->get('marca')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="modelo" :value="__('Modelo')" />
                                <x-text-input id="modelo" class="block mt-1 w-full" type="text" name="modelo" :value="old('modelo')" />
                                <x-input-error :messages="$errors->get('modelo')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Año y Kilometraje -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="anio" :value="__('Año')" />
                                <x-text-input id="anio" class="block mt-1 w-full" type="number" name="anio" :value="old('anio')" />
                                <x-input-error :messages="$errors->get('anio')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="kilometraje" :value="__('Kilometraje')" />
                                <x-text-input id="kilometraje" class="block mt-1 w-full" type="number" name="kilometraje" :value="old('kilometraje')" />
                                <x-input-error :messages="$errors->get('kilometraje')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('vehiculos.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Guardar Vehículo') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Ítem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('items.store') }}">
                        @csrf

                        <!-- Tipo -->
                        <div>
                            <x-input-label for="tipo" :value="__('Tipo de Ítem')" />
                            <select id="tipo" name="tipo" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="part" @selected(old('tipo') == 'part')>Repuesto</option>
                                <option value="labor" @selected(old('tipo') == 'labor')>Mano de Obra</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <x-input-label for="descripcion" :value="__('Descripción')" />
                            <x-text-input id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" :value="old('descripcion')" required autofocus />
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>

                        <!-- Código -->
                        <div class="mt-4">
                            <x-input-label for="codigo" :value="__('Código (SKU / Referencia)')" />
                            <x-text-input id="codigo" class="block mt-1 w-full" type="text" name="codigo" :value="old('codigo')" />
                            <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
                        </div>

                        <!-- Precio Unitario -->
                        <div class="mt-4">
                            <x-input-label for="precio_unitario" :value="__('Precio Unitario (€)')" />
                            <x-text-input id="precio_unitario" class="block mt-1 w-full" type="number" name="precio_unitario" :value="old('precio_unitario')" required step="0.01" />
                            <x-input-error :messages="$errors->get('precio_unitario')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('items.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Guardar Ítem') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

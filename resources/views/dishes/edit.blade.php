@extends('layouts.app')
@section('title', 'Editar Platillo')
@section('content')

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('dishes.update', $dish->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Editar Platillo</h2>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dish->available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $dish->available ? 'Disponible' : 'No disponible' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del platillo *</label>
                        <input type="text" name="name" required value="{{ old('name', $dish->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('description', $dish->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="price" step="0.01" required value="{{ old('price', $dish->price) }}"
                                class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="available" value="1" {{ old('available', $dish->available) ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                        </div>
                        <div class="ml-3 text-sm">
                            <label class="font-medium text-gray-700">Disponible para venta</label>
                            <p class="text-gray-500">El platillo estará disponible en el menú</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Ingredientes Requeridos</h2>

                <div id="ingredients-section" class="space-y-4">
                    @foreach($ingredients as $ingredient)
                        @php
                            $currentIngredient = $dish->ingredients->firstWhere('id', $ingredient->id);
                            $isSelected = $currentIngredient !== null;
                            $quantity = $isSelected ? $currentIngredient->pivot->quantity_required : '';
                        @endphp
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200 {{ $isSelected ? 'bg-green-50 border-green-200' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox"
                                        class="ingredient-checkbox w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2"
                                        data-id="{{ $ingredient->id }}" {{ $isSelected ? 'checked' : '' }}>
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $ingredient->name }}</span>
                                        <p class="text-sm text-gray-500">Disponible: {{ $ingredient->stock }}
                                            {{ $ingredient->unit }}</p>
                                        @if($isSelected)
                                            <p class="text-sm text-green-600">✓ Actualmente usado: {{ $quantity }}
                                                {{ $ingredient->unit }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="ingredients[{{ $ingredient->id }}]" placeholder="Cantidad"
                                        step="0.01" min="0" value="{{ $quantity }}" {{ !$isSelected ? 'disabled' : '' }}
                                        class="w-24 px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 disabled:bg-gray-100">
                                    <span class="text-sm text-gray-500">{{ $ingredient->unit }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('dishes.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                    Actualizar platillo
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.ingredient-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const input = document.querySelector(`input[name="ingredients[${this.dataset.id}]"]`);
                    input.disabled = !this.checked;
                    if (!this.checked) input.value = '';
                });
            });
        });
    </script>
@endsection
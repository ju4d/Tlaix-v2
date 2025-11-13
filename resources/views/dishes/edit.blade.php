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
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Ingredientes Requeridos
                    </h2>
                </div>

                <!-- Buscador de ingredientes -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-green-800 font-medium mb-3">Modifica los ingredientes necesarios para este platillo</p>
                            
                            <!-- Buscador -->
                            <div class="relative mb-4">
                                <input type="text" id="ingredientSearchInput" placeholder="Buscar ingredientes..." 
                                       class="w-full pl-10 pr-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Grid de ingredientes disponibles -->
                            <div id="availableIngredients" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto">
                                @foreach($ingredients as $ingredient)
                                    @php
                                        $currentIngredient = $dish->ingredients->firstWhere('id', $ingredient->id);
                                        $isSelected = $currentIngredient !== null;
                                    @endphp
                                    <div class="ingredient-card bg-white p-3 rounded-lg border-2 border-green-200 hover:border-green-500 hover:shadow-md cursor-pointer transition-all"
                                         data-ingredient-id="{{ $ingredient->id }}"
                                         data-selected="{{ $isSelected ? 'true' : 'false' }}"
                                         data-search="{{ strtolower($ingredient->name . ' ' . ($ingredient->category ?? '') . ' ' . $ingredient->unit) }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-900 text-sm mb-1">{{ $ingredient->name }}</h5>
                                                <div class="space-y-1">
                                                    <p class="text-xs text-gray-600">
                                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                        </svg>
                                                        Stock: <strong>{{ $ingredient->stock }} {{ $ingredient->unit }}</strong>
                                                    </p>
                                                    @if($ingredient->category)
                                                    <p class="text-xs text-gray-500">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800">{{ $ingredient->category }}</span>
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <button type="button" class="add-ingredient-btn bg-green-500 hover:bg-green-600 text-white rounded-full p-1.5 transition-colors" title="Agregar ingrediente">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div id="noIngredientsFound" class="hidden text-center py-4 text-gray-500">
                                <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <p class="text-sm">No se encontraron ingredientes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ingredientes seleccionados -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Ingredientes Seleccionados
                        <span id="selectedCount" class="ml-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">0</span>
                    </h4>
                    
                    <div id="selectedIngredients" class="space-y-3">
                        <div class="text-center py-8 text-gray-400">
                            <svg class="mx-auto h-12 w-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-sm">Haz clic en los ingredientes de arriba para agregarlos</p>
                        </div>
                    </div>
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

    <style>
    /* Estilos para las tarjetas de ingredientes */
    .ingredient-card {
        transition: all 0.2s ease;
        position: relative;
    }

    .ingredient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .ingredient-card:active {
        transform: translateY(-2px);
    }

    .ingredient-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 0.5rem;
        padding: 2px;
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .ingredient-card:hover::before {
        opacity: 0.5;
    }

    /* Scrollbar personalizado */
    #availableIngredients::-webkit-scrollbar {
        width: 8px;
    }

    #availableIngredients::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    #availableIngredients::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    #availableIngredients::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Animación de pulso para feedback */
    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    .selected-ingredient-item {
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    </style>

    <script>
        let selectedIngredients = new Map();
        let allIngredientsData = {!! json_encode($ingredients->map(function($ing) use ($dish) {
            $currentIngredient = $dish->ingredients->firstWhere('id', $ing->id);
            $isSelected = $currentIngredient !== null;
            $quantity = $isSelected ? $currentIngredient->pivot->quantity_required : '';
            
            return [
                'id' => $ing->id,
                'name' => $ing->name,
                'stock' => $ing->stock,
                'unit' => $ing->unit,
                'category' => $ing->category ?? '',
                'isSelected' => $isSelected,
                'quantity' => $quantity
            ];
        })->values()) !!};

        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar ingredientes ya seleccionados
            allIngredientsData.forEach(ingredient => {
                if (ingredient.isSelected) {
                    selectedIngredients.set(ingredient.id.toString(), {
                        id: ingredient.id,
                        name: ingredient.name,
                        stock: ingredient.stock,
                        unit: ingredient.unit,
                        category: ingredient.category,
                        quantity: ingredient.quantity
                    });
                }
            });

            // Renderizar ingredientes seleccionados
            renderSelectedIngredients();
            updateSelectedCount();

            // Event listeners para las tarjetas de ingredientes
            const ingredientCards = document.querySelectorAll('.ingredient-card');
            
            ingredientCards.forEach(card => {
                const addButton = card.querySelector('.add-ingredient-btn');
                const ingredientId = card.getAttribute('data-ingredient-id');
                
                // Click en el botón
                addButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    addIngredientToSelection(ingredientId, card);
                });
                
                // Click en la tarjeta completa
                card.addEventListener('click', () => {
                    addIngredientToSelection(ingredientId, card);
                });
            });

            // Event listener para el buscador
            const searchInput = document.getElementById('ingredientSearchInput');
            searchInput.addEventListener('input', function() {
                filterIngredients(this.value);
            });
        });

        function addIngredientToSelection(ingredientId, card) {
            // Si ya está seleccionado, no hacer nada
            if (selectedIngredients.has(ingredientId)) {
                // Mostrar feedback visual
                const selectedItem = document.getElementById(`selected-${ingredientId}`);
                if (selectedItem) {
                    selectedItem.classList.add('ring-2', 'ring-green-400');
                    setTimeout(() => {
                        selectedItem.classList.remove('ring-2', 'ring-green-400');
                    }, 500);
                    
                    // Scroll al ingrediente seleccionado
                    selectedItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    
                    // Enfocar el input de cantidad
                    const quantityInput = selectedItem.querySelector('input[type="number"]');
                    if (quantityInput) {
                        quantityInput.focus();
                        quantityInput.select();
                    }
                }
                return;
            }

            // Obtener datos del ingrediente
            const ingredient = allIngredientsData.find(ing => ing.id == ingredientId);
            if (!ingredient) return;

            // Agregar a la lista de seleccionados
            selectedIngredients.set(ingredientId, {
                id: ingredient.id,
                name: ingredient.name,
                stock: ingredient.stock,
                unit: ingredient.unit,
                category: ingredient.category,
                quantity: ingredient.quantity || ''
            });

            // Actualizar la vista
            renderSelectedIngredients();
            updateSelectedCount();

            // Feedback visual en la tarjeta
            card.classList.add('ring-2', 'ring-green-400');
            setTimeout(() => {
                card.classList.remove('ring-2', 'ring-green-400');
            }, 1000);

            // Enfocar el nuevo input de cantidad
            setTimeout(() => {
                const newItem = document.getElementById(`selected-${ingredientId}`);
                if (newItem) {
                    const quantityInput = newItem.querySelector('input[type="number"]');
                    if (quantityInput) {
                        quantityInput.focus();
                    }
                    newItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 100);
        }

        function removeIngredientFromSelection(ingredientId) {
            selectedIngredients.delete(ingredientId);
            renderSelectedIngredients();
            updateSelectedCount();
        }

        function renderSelectedIngredients() {
            const container = document.getElementById('selectedIngredients');
            
            if (selectedIngredients.size === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-400">
                        <svg class="mx-auto h-12 w-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-sm">Haz clic en los ingredientes de arriba para agregarlos</p>
                    </div>
                `;
                return;
            }

            let html = '';
            selectedIngredients.forEach((ingredient, id) => {
                html += `
                    <div id="selected-${id}" class="selected-ingredient-item bg-white border-2 border-green-300 rounded-lg p-3 hover:border-green-400 transition-all">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h5 class="font-semibold text-gray-900 text-sm">${ingredient.name}</h5>
                                    ${ingredient.category ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800 text-xs">${ingredient.category}</span>` : ''}
                                </div>
                                <p class="text-xs text-gray-500">Stock disponible: <strong>${ingredient.stock} ${ingredient.unit}</strong></p>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <input type="number"
                                           name="ingredients[${id}]"
                                           placeholder="Cantidad"
                                           step="0.01"
                                           min="0.01"
                                           required
                                           value="${ingredient.quantity}"
                                           class="w-24 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                           onchange="updateIngredientQuantity('${id}', this.value)">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 pointer-events-none">${ingredient.unit}</span>
                                </div>
                                
                                <button type="button" 
                                        onclick="removeIngredientFromSelection('${id}')"
                                        class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-colors"
                                        title="Eliminar ingrediente">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function updateIngredientQuantity(id, quantity) {
            if (selectedIngredients.has(id)) {
                const ingredient = selectedIngredients.get(id);
                ingredient.quantity = quantity;
                selectedIngredients.set(id, ingredient);
            }
        }

        function updateSelectedCount() {
            document.getElementById('selectedCount').textContent = selectedIngredients.size;
        }

        function filterIngredients(searchTerm) {
            const searchLower = searchTerm.toLowerCase().trim();
            const ingredientCards = document.querySelectorAll('.ingredient-card');
            const noResultsDiv = document.getElementById('noIngredientsFound');
            const availableDiv = document.getElementById('availableIngredients');
            
            let visibleCount = 0;
            
            ingredientCards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                if (searchData.includes(searchLower)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (visibleCount === 0 && searchLower !== '') {
                availableDiv.classList.add('hidden');
                noResultsDiv.classList.remove('hidden');
            } else {
                availableDiv.classList.remove('hidden');
                noResultsDiv.classList.add('hidden');
            }
        }
    </script>
@endsection
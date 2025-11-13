@extends('layouts.app')
@section('title','Crear Nuevo Pedido')
@section('content')

<div class="max-w-6xl">
    <!-- Alerts -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">¡Errores en el formulario!</h3>
                    <div class="mt-2">
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" id="orderForm" class="space-y-8">
        @csrf
        
        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Información del Pedido
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor *</label>
                    <select name="supplier_id" required id="supplierSelect"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('supplier_id') border-red-500 @enderror">
                        <option value="">Seleccionar Proveedor</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }} - {{ $supplier->contact }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha del Pedido *</label>
                    <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <div class="relative">
                        <select disabled class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                            <option value="pending" selected>Pendiente (automático)</option>
                        </select>
                        <input type="hidden" name="status" value="pending">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Los pedidos siempre se crean en estado "Pendiente" y pueden ser marcados como "Recibidos" desde la lista de pedidos.</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Productos del Pedido
                </h3>
                <button type="button" id="addItem" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Agregar Producto
                </button>
            </div>

            <!-- Panel informativo de ingredientes del proveedor -->
            <div id="supplierInfo" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 hidden">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-black mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <div class="flex-1">

                        
                        <!-- Buscador de ingredientes -->
                        <div class="relative mb-3">
                            <input type="text" id="ingredientSearchInput" placeholder="Buscar ingredientes..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-sm bg-white">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div id="availableIngredients" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-64 overflow-y-auto">
                            <!-- Se llenará dinámicamente -->
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

            <div id="orderItems" class="space-y-4">
                <!-- Template para items -->
                <div class="order-item hidden" id="item-template">
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition duration-200 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            <div class="md:col-span-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ingrediente *</label>
                                <select name="items[0][ingredient_id]" class="ingredient-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-sm" disabled>
                                    <option value="">Primero seleccione un proveedor</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad *</label>
                                <input type="number" name="items[0][quantity]" step="0.01" min="0" 
                                       class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-sm" 
                                       placeholder="0" disabled>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Costo Unitario *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">$</span>
                                    </div>
                                    <input type="number" name="items[0][unit_cost]" step="0.01" min="0" 
                                           class="unit-cost-input w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-sm" 
                                           placeholder="0.00" disabled>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">$</span>
                                    </div>
                                    <input type="text" class="subtotal-display w-full pl-7 pr-3 py-2 border border-gray-200 rounded-md bg-gray-100 text-gray-700 text-sm font-medium" 
                                           readonly value="0.00">
                                </div>
                            </div>

                            <div class="md:col-span-1 flex items-end">
                                <button type="button" class="remove-item w-full bg-red-600 hover:bg-red-700 text-white p-2 rounded-md transition duration-200">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Datos de ingredientes por proveedor (para JavaScript) -->
        <script type="application/json" id="ingredients-data">
            {!! json_encode($ingredients->groupBy('supplier_id')->map(function($items) {
                return $items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'cost' => $item->cost ?? 0,
                        'unit' => $item->unit,
                        'stock' => $item->stock,
                        'category' => $item->category ?? ''
                    ];
                });
            })) !!}
        </script>

        <!-- Total del pedido -->
        <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-50 border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Total del Pedido</p>
                        <p id="orderTotal" class="text-3xl font-bold text-gray-700">$0.00</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Productos agregados</p>
                    <p id="itemCount" class="text-lg font-semibold text-gray-700">0</p>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('orders.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                Cancelar
            </a>
            <button type="submit" id="submitBtn"
                    class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 inline-flex items-center">
                <svg id="submitIcon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span id="submitText">Crear Pedido</span>
                <svg id="submitLoading" class="animate-spin h-5 w-5 mr-2 hidden" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>
</form>

<style>
/* Estilos para campos deshabilitados */
select:disabled, input:disabled {
    background-color: #f9fafb !important;
    color: #9ca3af !important;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Animación para ingredientes */
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
    background: linear-gradient(135deg, #00ffaa, #3b82f6);
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

/* Animación de carga */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Mejorar inputs de número */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 1;
}

/* Animación de pulso para feedback */
@keyframes pulse-ring {
    0% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
    }
}
</style>

<script>
let itemIndex = 0;
let ingredientsData = {};
let allIngredients = [];

document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos de ingredientes
    const ingredientsDataElement = document.getElementById('ingredients-data');
    if (ingredientsDataElement) {
        ingredientsData = JSON.parse(ingredientsDataElement.textContent);
    }

    // Agregar el primer item por defecto
    addOrderItem();

    // Event listener para cambio de proveedor
    document.getElementById('supplierSelect').addEventListener('change', function() {
        updateIngredientsForAllItems();
        updateSupplierInfo();
    });

    // Event listener para agregar items
    document.getElementById('addItem').addEventListener('click', addOrderItem);

    // Event listener para el buscador de ingredientes
    const searchInput = document.getElementById('ingredientSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterIngredients(this.value);
        });
    }

    // Event listener para el formulario
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        // Remover required de campos ocultos antes de validar
        const hiddenInputs = document.querySelectorAll('#item-template input, #item-template select');
        hiddenInputs.forEach(input => {
            input.removeAttribute('required');
        });

        if (!validateForm()) {
            e.preventDefault();
        } else {
            // Mostrar loading
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            const submitIcon = document.getElementById('submitIcon');

            submitBtn.disabled = true;
            submitText.textContent = 'Creando...';
            submitIcon.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            submitBtn.classList.add('opacity-75');
        }
    });
});

function addOrderItem() {
    const template = document.getElementById('item-template');
    const newItem = template.cloneNode(true);

    // Actualizar IDs y nombres para el nuevo item
    newItem.id = `item-${itemIndex}`;
    newItem.classList.remove('hidden');

    // Actualizar nombres de inputs
    const inputs = newItem.querySelectorAll('input, select');
    inputs.forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace('[0]', `[${itemIndex}]`));
        }
        // Asegurar que los campos del nuevo item tengan required
        if (input.classList.contains('ingredient-select') ||
            input.classList.contains('quantity-input') ||
            input.classList.contains('unit-cost-input')) {
            input.setAttribute('required', 'required');
        }
    });

    // Agregar event listeners
    setupItemEventListeners(newItem);

    // Actualizar ingredientes disponibles para este item
    updateIngredientsForItem(newItem);

    // Agregar al contenedor
    document.getElementById('orderItems').appendChild(newItem);
    itemIndex++;

    // Actualizar contador y botones
    updateItemCount();
    updateRemoveButtons();
}

function setupItemEventListeners(item) {
    const ingredientSelect = item.querySelector('.ingredient-select');
    const quantityInput = item.querySelector('.quantity-input');
    const unitCostInput = item.querySelector('.unit-cost-input');
    const subtotalDisplay = item.querySelector('.subtotal-display');
    const removeButton = item.querySelector('.remove-item');

    // Auto-llenar costo cuando se selecciona ingrediente
    ingredientSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const cost = selectedOption.getAttribute('data-cost');
            unitCostInput.value = cost || 0;
            calculateSubtotal(item);
        }
        this.classList.remove('border-red-500');
    });

    // Calcular subtotal cuando cambia cantidad o costo
    quantityInput.addEventListener('input', () => {
        calculateSubtotal(item);
        quantityInput.classList.remove('border-red-500');
    });

    unitCostInput.addEventListener('input', () => {
        calculateSubtotal(item);
        unitCostInput.classList.remove('border-red-500');
    });

    // Eliminar item
    removeButton.addEventListener('click', function() {
        if (document.querySelectorAll('.order-item:not(#item-template):not(.hidden)').length > 1) {
            item.remove();
            calculateTotal();
            updateItemCount();
            updateRemoveButtons();
        } else {
            alert('Debe haber al menos un producto en el pedido.');
        }
    });
}

function updateIngredientsForAllItems() {
    const items = document.querySelectorAll('.order-item:not(#item-template):not(.hidden)');
    items.forEach(item => {
        updateIngredientsForItem(item);
    });
}

function updateIngredientsForItem(item) {
    const ingredientSelect = item.querySelector('.ingredient-select');
    const quantityInput = item.querySelector('.quantity-input');
    const unitCostInput = item.querySelector('.unit-cost-input');
    const supplierId = document.getElementById('supplierSelect').value;

    // Limpiar opciones actuales
    ingredientSelect.innerHTML = '';

    if (!supplierId) {
        ingredientSelect.innerHTML = '<option value="">Primero seleccione un proveedor</option>';
        ingredientSelect.disabled = true;
        quantityInput.disabled = true;
        unitCostInput.disabled = true;
        return;
    }

    // Habilitar campos
    ingredientSelect.disabled = false;
    quantityInput.disabled = false;
    unitCostInput.disabled = false;

    // Agregar opción por defecto
    ingredientSelect.innerHTML = '<option value="">Seleccionar Ingrediente</option>';

    // Agregar ingredientes del proveedor seleccionado
    const supplierIngredients = ingredientsData[supplierId] || [];

    if (supplierIngredients.length === 0) {
        ingredientSelect.innerHTML = '<option value="">Este proveedor no tiene ingredientes registrados</option>';
        ingredientSelect.disabled = true;
        quantityInput.disabled = true;
        unitCostInput.disabled = true;
        return;
    }

    supplierIngredients.forEach(ingredient => {
        const option = document.createElement('option');
        option.value = ingredient.id;
        option.textContent = `${ingredient.name} (${ingredient.stock} ${ingredient.unit} disponibles)`;
        option.setAttribute('data-cost', ingredient.cost);
        option.setAttribute('data-unit', ingredient.unit);
        ingredientSelect.appendChild(option);
    });
}

function calculateSubtotal(item) {
    const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
    const unitCost = parseFloat(item.querySelector('.unit-cost-input').value) || 0;
    const subtotal = quantity * unitCost;

    item.querySelector('.subtotal-display').value = subtotal.toFixed(2);
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    const subtotals = document.querySelectorAll('.order-item:not(#item-template):not(.hidden) .subtotal-display');

    subtotals.forEach(subtotal => {
        total += parseFloat(subtotal.value) || 0;
    });

    document.getElementById('orderTotal').textContent = `$${total.toFixed(2)}`;
}

function updateItemCount() {
    const count = document.querySelectorAll('.order-item:not(#item-template):not(.hidden)').length;
    document.getElementById('itemCount').textContent = count;
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.order-item:not(#item-template):not(.hidden)');
    items.forEach(item => {
        const removeButton = item.querySelector('.remove-item');
        if (removeButton) {
            removeButton.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
}

function updateSupplierInfo() {
    const supplierId = document.getElementById('supplierSelect').value;
    const supplierInfo = document.getElementById('supplierInfo');
    const availableIngredients = document.getElementById('availableIngredients');
    const searchInput = document.getElementById('ingredientSearchInput');

    if (!supplierId) {
        supplierInfo.classList.add('hidden');
        return;
    }

    const supplierIngredients = ingredientsData[supplierId] || [];
    allIngredients = supplierIngredients;

    if (supplierIngredients.length === 0) {
        supplierInfo.classList.remove('hidden');
        availableIngredients.innerHTML = '<div class="col-span-full text-center py-4"><p class="text-red-600 font-medium">Este proveedor no tiene ingredientes registrados.</p></div>';
        if (searchInput) searchInput.disabled = true;
        return;
    }

    supplierInfo.classList.remove('hidden');
    if (searchInput) {
        searchInput.disabled = false;
        searchInput.value = '';
    }
    
    renderIngredients(supplierIngredients);
}

function renderIngredients(ingredients) {
    const availableIngredients = document.getElementById('availableIngredients');
    const noIngredientsFound = document.getElementById('noIngredientsFound');
    
    availableIngredients.innerHTML = '';

    if (ingredients.length === 0) {
        availableIngredients.classList.add('hidden');
        noIngredientsFound.classList.remove('hidden');
        return;
    }

    availableIngredients.classList.remove('hidden');
    noIngredientsFound.classList.add('hidden');

    ingredients.forEach(ingredient => {
        const card = document.createElement('div');
        card.className = 'ingredient-card bg-white p-3 rounded-lg border-2 border-blue-200 hover:border-green-500 hover:shadow-md cursor-pointer transition-all';
        card.setAttribute('data-ingredient-id', ingredient.id);
        card.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h5 class="font-semibold text-gray-900 text-sm mb-1">${ingredient.name}</h5>
                    <div class="space-y-1">
                        <p class="text-xs text-gray-600">
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Stock: <span class="font-medium">${ingredient.stock} ${ingredient.unit}</span>
                            </span>
                        </p>
                        <p class="text-xs text-green-700 font-medium">
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                $${parseFloat(ingredient.cost || 0).toFixed(2)}
                            </span>
                        </p>
                        ${ingredient.category ? `<p class="text-xs text-gray-500"><span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">${ingredient.category}</span></p>` : ''}
                    </div>
                </div>
                <div class="ml-2">
                    <button type="button" class="add-ingredient-btn bg-green-500 hover:bg-green-600 text-white rounded-full p-1.5 transition-colors" title="Agregar al pedido">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        // Evento para agregar ingrediente al hacer clic en la card o en el botón
        const addButton = card.querySelector('.add-ingredient-btn');
        addButton.addEventListener('click', (e) => {
            e.stopPropagation();
            addIngredientToOrder(ingredient);
        });
        
        card.addEventListener('click', () => {
            addIngredientToOrder(ingredient);
        });
        
        availableIngredients.appendChild(card);
    });
}

function addIngredientToOrder(ingredient) {
    // Buscar si ya existe un item vacío o crear uno nuevo
    let targetItem = findEmptyItem();
    
    if (!targetItem) {
        // No hay items vacíos, crear uno nuevo
        addOrderItem();
        targetItem = document.querySelector('.order-item:not(#item-template):not(.hidden):last-child');
    }
    
    // Llenar el item con los datos del ingrediente
    const ingredientSelect = targetItem.querySelector('.ingredient-select');
    const quantityInput = targetItem.querySelector('.quantity-input');
    const unitCostInput = targetItem.querySelector('.unit-cost-input');
    
    // Seleccionar el ingrediente
    ingredientSelect.value = ingredient.id;
    
    // Llenar el costo
    unitCostInput.value = ingredient.cost || 0;
    
    // Enfocar el campo de cantidad para que el usuario ingrese la cantidad
    quantityInput.value = '';
    quantityInput.focus();
    
    // Mostrar feedback visual
    targetItem.classList.add('ring-2', 'ring-green-400');
    setTimeout(() => {
        targetItem.classList.remove('ring-2', 'ring-green-400');
    }, 1000);
    
    // Scroll al item
    targetItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    calculateSubtotal(targetItem);
}

function findEmptyItem() {
    const items = document.querySelectorAll('.order-item:not(#item-template):not(.hidden)');
    
    for (let item of items) {
        const ingredientSelect = item.querySelector('.ingredient-select');
        const quantityInput = item.querySelector('.quantity-input');
        
        // Si el item no tiene ingrediente seleccionado o no tiene cantidad, considerarlo vacío
        if (!ingredientSelect.value || !quantityInput.value || parseFloat(quantityInput.value) === 0) {
            return item;
        }
    }
    
    return null;
}

function filterIngredients(searchTerm) {
    const filtered = allIngredients.filter(ingredient => {
        const searchLower = searchTerm.toLowerCase();
        return ingredient.name.toLowerCase().includes(searchLower) ||
               (ingredient.category && ingredient.category.toLowerCase().includes(searchLower)) ||
               ingredient.unit.toLowerCase().includes(searchLower);
    });

    renderIngredients(filtered);
}

function validateForm() {
    const supplier = document.getElementById('supplierSelect').value;
    if (!supplier) {
        alert('Por favor seleccione un proveedor.');
        document.getElementById('supplierSelect').focus();
        return false;
    }

    const supplierIngredients = ingredientsData[supplier] || [];
    if (supplierIngredients.length === 0) {
        alert('El proveedor seleccionado no tiene ingredientes registrados. Por favor seleccione otro proveedor.');
        document.getElementById('supplierSelect').focus();
        return false;
    }

    const visibleItems = document.querySelectorAll('.order-item:not(#item-template):not(.hidden)');
    let hasValidItems = false;
    let firstErrorField = null;

    for (let item of visibleItems) {
        const ingredient = item.querySelector('.ingredient-select').value;
        const quantity = parseFloat(item.querySelector('.quantity-input').value);
        const unitCost = parseFloat(item.querySelector('.unit-cost-input').value);

        if (ingredient || quantity > 0 || unitCost >= 0) {
            if (!ingredient) {
                if (!firstErrorField) firstErrorField = item.querySelector('.ingredient-select');
                item.querySelector('.ingredient-select').classList.add('border-red-500');
            } else {
                item.querySelector('.ingredient-select').classList.remove('border-red-500');
            }

            if (!quantity || quantity <= 0) {
                if (!firstErrorField) firstErrorField = item.querySelector('.quantity-input');
                item.querySelector('.quantity-input').classList.add('border-red-500');
            } else {
                item.querySelector('.quantity-input').classList.remove('border-red-500');
            }

            if (unitCost < 0 || isNaN(unitCost)) {
                if (!firstErrorField) firstErrorField = item.querySelector('.unit-cost-input');
                item.querySelector('.unit-cost-input').classList.add('border-red-500');
            } else {
                item.querySelector('.unit-cost-input').classList.remove('border-red-500');
            }

            if (ingredient && quantity > 0 && unitCost >= 0) {
                hasValidItems = true;
            }
        }
    }

    if (!hasValidItems) {
        alert('Por favor agregue al menos un producto válido al pedido.');
        if (firstErrorField) firstErrorField.focus();
        return false;
    }

    if (firstErrorField) {
        alert('Por favor complete todos los campos marcados en rojo.');
        firstErrorField.focus();
        return false;
    }

    return true;
}
</script>
@endsection

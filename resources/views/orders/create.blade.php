<!-- resources/views/orders/create.blade.php -->
@extends('layouts.app')
@section('title','Crear Nuevo Pedido')
@section('content')

@if ($errors->any())
    <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #f5c6cb;">
        <strong>¡Errores en el formulario!</strong>
        <ul style="margin: 10px 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('orders.store') }}" id="orderForm">
    @csrf
    <div class="card">
        <h3>Información del Pedido</h3>

        <label>Proveedor *</label>
        <select name="supplier_id" required id="supplierSelect">
            <option value="">Seleccionar Proveedor</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }} - {{ $supplier->contact }}
                </option>
            @endforeach
        </select>

        <label>Fecha del Pedido *</label>
        <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}">

        <label>Estado</label>
        <select name="status" disabled style="background: #f5f5f5; color: #666;">
            <option value="pending" selected>Pendiente (automático)</option>
        </select>
        <input type="hidden" name="status" value="pending">
        <small style="color: #666; font-style: italic;">Los pedidos siempre se crean en estado "Pendiente" y pueden ser marcados como "Recibidos" desde la lista de pedidos.</small>

        <h3>Productos del Pedido</h3>

        <!-- Panel informativo de ingredientes del proveedor -->
        <div id="supplierInfo" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; margin-bottom: 20px; display: none;">
            <h4 style="margin-top: 0; color: #495057;">📦 Productos disponibles de este proveedor:</h4>
            <div id="availableIngredients" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 10px;">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>

        <div id="orderItems">
            <div class="order-item" id="item-template" style="display: none;">
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr; gap: 10px; align-items: center; margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                    <select name="items[0][ingredient_id]" class="ingredient-select" disabled>
                        <option value="">Primero seleccione un proveedor</option>
                    </select>

                    <div>
                        <label style="font-size: 0.9em; color: #666;">Cantidad</label>
                        <input type="number" name="items[0][quantity]" step="0.01" min="0" class="quantity-input" placeholder="0" disabled>
                    </div>

                    <div>
                        <label style="font-size: 0.9em; color: #666;">Costo Unitario</label>
                        <input type="number" name="items[0][unit_cost]" step="0.01" min="0" class="unit-cost-input" placeholder="0.00" disabled>
                    </div>

                    <div>
                        <label style="font-size: 0.9em; color: #666;">Subtotal</label>
                        <input type="number" class="subtotal-display" readonly style="background: #f5f5f5; border: 1px solid #ddd;" value="0.00">
                    </div>

                    <button type="button" class="remove-item" style="background: #e74c3c; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 4px;">✖</button>
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
                        'stock' => $item->stock
                    ];
                });
            })) !!}
        </script>

        <button type="button" id="addItem" style="background: #27ae60; color: white; margin: 10px 0;">
            ➕ Agregar Producto
        </button>

        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <strong>Total del Pedido:</strong>
                <span id="orderTotal" style="font-size: 1.3em; color: #27ae60;">$0.00</span>
            </div>
        </div>

        <div style="margin-top: 25px;">
            <button type="submit" id="submitBtn" style="background: #3498db; color: white; padding: 12px 20px; font-size: 1.1em;">
                <span id="submitText">Crear Pedido</span>
                <span id="submitLoading" style="display: none;">Creando...</span>
            </button>
            <a href="{{ route('orders.index') }}" style="margin-left: 15px; color: #7f8c8d; text-decoration: none;">Cancelar</a>
        </div>
    </div>
</form>

<style>
.order-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    margin-bottom: 15px;
}

.order-item:hover {
    border-color: #3498db;
}

input[readonly] {
    background: #f8f9fa !important;
    color: #6c757d;
}

.remove-item:hover {
    background: #c0392b !important;
}

#addItem:hover {
    background: #229954 !important;
}

button[type="submit"]:hover {
    background: #2980b9 !important;
}

/* Estilos para campos deshabilitados */
select:disabled, input:disabled {
    background: #f8f9fa !important;
    color: #6c757d !important;
    cursor: not-allowed;
}

/* Animación para el panel de información del proveedor */
#supplierInfo {
    transition: all 0.3s ease;
}

/* Estilos para campos con error */
.error-field {
    border-color: #e74c3c !important;
    box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
}

/* Mejorar la apariencia de los select */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}
</style>

<script>
let itemIndex = 0;
let ingredientsData = {};

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

            submitBtn.disabled = true;
            submitText.style.display = 'none';
            submitLoading.style.display = 'inline';
            submitBtn.style.background = '#95a5a6';
        }
    });
});

function addOrderItem() {
    const template = document.getElementById('item-template');
    const newItem = template.cloneNode(true);

    // Actualizar IDs y nombres para el nuevo item
    newItem.id = `item-${itemIndex}`;
    newItem.style.display = 'block';

    // Actualizar nombres de inputs y remover required del template
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

    // Actualizar botones de eliminar
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

        // Limpiar estilos de error
        this.style.borderColor = '';
    });

    // Calcular subtotal cuando cambia cantidad o costo
    quantityInput.addEventListener('input', () => {
        calculateSubtotal(item);
        quantityInput.style.borderColor = '';
    });

    unitCostInput.addEventListener('input', () => {
        calculateSubtotal(item);
        unitCostInput.style.borderColor = '';
    });

    // Eliminar item
    removeButton.addEventListener('click', function() {
        if (document.querySelectorAll('.order-item:not(#item-template)').length > 1) {
            item.remove();
            calculateTotal();
            updateRemoveButtons();
        } else {
            alert('Debe haber al menos un producto en el pedido.');
        }
    });
}

function updateIngredientsForAllItems() {
    const items = document.querySelectorAll('.order-item:not(#item-template)');
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
        // No hay proveedor seleccionado
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
    const subtotals = document.querySelectorAll('.subtotal-display');

    subtotals.forEach(subtotal => {
        total += parseFloat(subtotal.value) || 0;
    });

    document.getElementById('orderTotal').textContent = `${total.toFixed(2)}`;
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.order-item:not(#item-template)');
    const removeButtons = document.querySelectorAll('.remove-item');

    removeButtons.forEach((button, index) => {
        button.style.display = items.length > 1 ? 'block' : 'none';
    });
}

function validateForm() {
    const supplier = document.getElementById('supplierSelect').value;
    if (!supplier) {
        alert('Por favor seleccione un proveedor.');
        document.getElementById('supplierSelect').focus();
        return false;
    }

    // Verificar que el proveedor tenga ingredientes
    const supplierIngredients = ingredientsData[supplier] || [];
    if (supplierIngredients.length === 0) {
        alert('El proveedor seleccionado no tiene ingredientes registrados. Por favor seleccione otro proveedor.');
        document.getElementById('supplierSelect').focus();
        return false;
    }

    const visibleItems = document.querySelectorAll('.order-item:not(#item-template)');
    let hasValidItems = false;
    let firstErrorField = null;

    for (let item of visibleItems) {
        const ingredient = item.querySelector('.ingredient-select').value;
        const quantity = parseFloat(item.querySelector('.quantity-input').value);
        const unitCost = parseFloat(item.querySelector('.unit-cost-input').value);

        // Verificar si el item tiene al menos un campo lleno
        if (ingredient || quantity > 0 || unitCost >= 0) {
            // Si tiene algún campo lleno, validar que todos estén completos
            if (!ingredient) {
                if (!firstErrorField) {
                    firstErrorField = item.querySelector('.ingredient-select');
                }
                item.querySelector('.ingredient-select').style.borderColor = '#e74c3c';
            } else {
                item.querySelector('.ingredient-select').style.borderColor = '';
            }

            if (!quantity || quantity <= 0) {
                if (!firstErrorField) {
                    firstErrorField = item.querySelector('.quantity-input');
                }
                item.querySelector('.quantity-input').style.borderColor = '#e74c3c';
            } else {
                item.querySelector('.quantity-input').style.borderColor = '';
            }

            if (unitCost < 0 || isNaN(unitCost)) {
                if (!firstErrorField) {
                    firstErrorField = item.querySelector('.unit-cost-input');
                }
                item.querySelector('.unit-cost-input').style.borderColor = '#e74c3c';
            } else {
                item.querySelector('.unit-cost-input').style.borderColor = '';
            }

            // Si todos los campos están completos, es un item válido
            if (ingredient && quantity > 0 && unitCost >= 0) {
                hasValidItems = true;
            }
        }
    }

    if (!hasValidItems) {
        alert('Por favor agregue al menos un producto válido al pedido.');
        if (firstErrorField) {
            firstErrorField.focus();
        }
        return false;
    }

    if (firstErrorField) {
        alert('Por favor complete todos los campos marcados en rojo.');
        firstErrorField.focus();
        return false;
    }

    return true;
}

function updateSupplierInfo() {
    const supplierId = document.getElementById('supplierSelect').value;
    const supplierInfo = document.getElementById('supplierInfo');
    const availableIngredients = document.getElementById('availableIngredients');

    if (!supplierId) {
        supplierInfo.style.display = 'none';
        return;
    }

    const supplierIngredients = ingredientsData[supplierId] || [];

    if (supplierIngredients.length === 0) {
        supplierInfo.style.display = 'block';
        availableIngredients.innerHTML = '<p style="color: #e74c3c; margin: 0;">Este proveedor no tiene ingredientes registrados.</p>';
        return;
    }

    supplierInfo.style.display = 'block';
    availableIngredients.innerHTML = '';

    supplierIngredients.forEach(ingredient => {
        const ingredientCard = document.createElement('div');
        ingredientCard.style.cssText = 'background: white; padding: 10px; border-radius: 4px; border-left: 4px solid #3498db; font-size: 0.9em;';
        ingredientCard.innerHTML = `
            <strong>${ingredient.name}</strong><br>
            <span style="color: #666;">Stock: ${ingredient.stock} ${ingredient.unit}</span><br>
            <span style="color: #27ae60;">Costo: ${parseFloat(ingredient.cost || 0).toFixed(2)}</span>
        `;
        availableIngredients.appendChild(ingredientCard);
    });
}
</script>
@endsection

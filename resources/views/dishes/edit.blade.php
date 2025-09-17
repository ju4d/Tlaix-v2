<!-- resources/views/dishes/edit.blade.php -->
@extends('layouts.app')
@section('title','Edit Dish')
@section('content')
<form method="POST" action="{{ route('dishes.update', $dish->id) }}">
    @csrf
    @method('PUT')
    <div class="card">
        <label>Dish Name *</label>
        <input type="text" name="name" required value="{{ old('name', $dish->name) }}">

        <label>Description</label>
        <textarea name="description" rows="3">{{ old('description', $dish->description) }}</textarea>

        <label>Price *</label>
        <input type="number" name="price" step="0.01" required value="{{ old('price', $dish->price) }}">

        <label>
            <input type="checkbox" name="available" value="1" {{ old('available', $dish->available) ? 'checked' : '' }}>
            Available
        </label>

        <h3>Ingredients</h3>
        <div id="ingredients-section">
            @foreach($ingredients as $ingredient)
            @php
                $currentIngredient = $dish->ingredients->firstWhere('id', $ingredient->id);
                $isSelected = $currentIngredient !== null;
                $quantity = $isSelected ? $currentIngredient->pivot->quantity_required : '';
            @endphp
            <div style="margin: 10px 0; padding: 10px; border: 1px solid #ddd;">
                <label>
                    <input type="checkbox" class="ingredient-checkbox" data-id="{{ $ingredient->id }}" {{ $isSelected ? 'checked' : '' }}>
                    {{ $ingredient->name }} ({{ $ingredient->stock }} {{ $ingredient->unit }} available)
                </label>
                <input type="number"
                       name="ingredients[{{ $ingredient->id }}]"
                       placeholder="Quantity required"
                       step="0.01"
                       min="0"
                       value="{{ $quantity }}"
                       style="margin-left: 10px; width: 150px;"
                       {{ !$isSelected ? 'disabled' : '' }}>
            </div>
            @endforeach
        </div>

        <button type="submit">Update Dish</button>
        <a href="{{ route('dishes.index') }}" style="margin-left:10px">Cancel</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.ingredient-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const input = document.querySelector(`input[name="ingredients[${this.dataset.id}]"]`);
            input.disabled = !this.checked;
            if (!this.checked) input.value = '';
        });
    });
});
</script>
@endsection
